<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Tüm Urunleri Getir
$app->get('/api/urunler',function(Request $request,Response $response){
    $sql = "SELECT * from urunler";
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

//  Tek bir urun bul (/api/urun/{id})
$app->get('/api/urun/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * from urunler WHERE urun_id=$id";
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

// Ürün Ekle (/api/urun/ekle)
// Veri JSON formatında passlanmalıdır.
$app->post('/api/urun/ekle',function(Request $request,Response $response){
    $urun_adi = $request->getParsedBody()['urun_adi'];
    $urun_turu = $request->getParsedBody()['urun_turu'];

    $sql = "INSERT INTO urunler (urun_adi,urun_turu) VALUES (:urun_adi,:urun_turu)";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->bindParam(':urun_adi',$urun_adi);
        $stmt->bindParam(':urun_turu',$urun_turu);
        $stmt->execute();

        // veri alındıysa
        echo '{"Bilgilendirme:" {"text": "Ürün Eklendi"}}';
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//  Ürün Güncelle (/api/urun/guncelle/{id})
// Veri JSON formatında passlanmalıdır.
$app->post('/api/urun/guncelle/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $urun_adi = $request->getParsedBody()['urun_adi'];
    $urun_turu = $request->getParsedBody()['urun_turu'];

    $sql = "UPDATE urunler SET
            urun_adi = :urun_adi,
            urun_turu = :urun_turu
            WHERE urun_id = $id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->bindParam(':urun_adi',$urun_adi);
        $stmt->bindParam(':urun_turu',$urun_turu);
        $stmt->execute();

        // veri alındıysa
        echo '{"Bilgilendirme:" {"text": "Ürün Güncellendi"}}';
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//  Ürün Sil (/api/urun/sil/{id})
$app->delete('/api/urun/sil/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM urunler WHERE urun_id=$id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->execute();

        echo '{"Bilgilendirme:" {"text": "id:'.$id.' olan Ürün Silindi"}}';

        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

include '../src/routes/siparisler.php';