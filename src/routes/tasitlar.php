<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Tüm Tasitlari Getir
$app->get('/api/tasitlar',function(Request $request,Response $response){
    $sql = "SELECT tasitlar.id, tasitlar.marka, tasitlar.plaka, personel.adi, personel.soyadi,personel.ceptel 
            FROM tasitlar INNER JOIN personel on tasitlar.zimmetli_personel=personel.personel_id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);

        // veri alındıysa
        if (isset($customers)) {
            echo $db->trCharConverterJson($customers);
        }else{
            echo "nothing is fetched";
        }
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//  Tek bir taşıt bul (/api/tasit/{id})
$app->get('/api/tasit/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT tasitlar.marka, tasitlar.plaka, personel.adi, personel.soyadi,personel.ceptel 
            FROM tasitlar INNER JOIN personel on tasitlar.zimmetli_personel=personel.personel_id WHERE id=$id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->query($sql);
        $customer = $stmt->fetchAll(PDO::FETCH_OBJ);

        // veri alındıysa
        if (isset($customer)) {
            echo $db->trCharConverterJson($customer);
        }else{
            echo "nothing is fetched";
        }
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Taşıt Ekle (/api/tasit/ekle)
// Veri JSON formatında passlanmalıdır.
$app->post('/api/tasit/ekle',function(Request $request,Response $response){
    $marka = $request->getParsedBody()['marka'];
    $plaka = $request->getParsedBody()['plaka'];
    $zimmetli_personel = $request->getParsedBody()['zimmetli_personel'];

    $sql = "INSERT INTO tasitlar (marka,plaka,zimmetli_personel) VALUES (:marka,:plaka,:zimmetli_personel)";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->bindParam(':marka',$marka);
        $stmt->bindParam(':plaka',$plaka);
        $stmt->bindParam(':zimmetli_personel',$zimmetli_personel);
        $stmt->execute();

        // veri alındıysa
        echo '{"Bilgilendirme:" {"text": "Taşıt Eklendi"}}';
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//  Taşıt Güncelle (/api/tasit/guncelle/{id})
// Veri JSON formatında passlanmalıdır.
$app->post('/api/tasit/guncelle/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $marka = $request->getParsedBody()['marka'];
    $plaka = $request->getParsedBody()['plaka'];
    $zimmetli_personel = $request->getParsedBody()['zimmetli_personel'];

    $sql = "UPDATE tasitlar SET
            marka = :marka,
            plaka = :plaka,
            zimmetli_personel = :zimmetli_personel
            WHERE id = $id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->bindParam(':marka',$marka);
        $stmt->bindParam(':plaka',$plaka);
        $stmt->bindParam(':zimmetli_personel',$zimmetli_personel);
        $stmt->execute();

        // veri alındıysa
        echo '{"Bilgilendirme:" {"text": "Taşıt Güncellendi"}}';
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//  Taşıt Sil (/api/tasit/sil/{id})
$app->delete('/api/tasit/sil/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM tasitlar WHERE id=$id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->execute();

        echo '{"Bilgilendirme:" {"text": "id:'.$id.' olan Taşıt Silindi"}}';

        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

include '../src/routes/urunler.php';