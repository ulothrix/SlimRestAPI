<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// Müşteri Login
$app->post('/api/login',function(Request $request,Response $response){
    $email = $request->getParsedBody()['musteri_email'];
    $sifre = $request->getParsedBody()['musteri_sifre'];

    $sql = "SELECT * FROM musteriler WHERE musteri_email='".$email."' AND musteri_sifre='".$sifre."'";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->query($sql);
        // satır var mı yok mu bakabilmek için adetini check ediyoruz.
        $count = $stmt->rowCount();
        // ilgili koşullara uyan bilgi fetchleniyor.
        $customer = $stmt->fetch(PDO::FETCH_OBJ);

        // veri alındıysa
        if ($count>0) {
            echo $db->trCharConverterJson($customer);
        }else{
            echo json_encode(Array('isLogin' => '0'));
        }
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Tüm Müşterileri Getir
$app->get('/api/musteriler',function(Request $request,Response $response){
    $sql = "SELECT * FROM musteriler";
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

//  Tek bir müşteri bul (/api/musteri/{id})
$app->get('/api/musteri/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM musteriler WHERE musteri_id=$id";
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

//  Musteri Ekle (/api/musteri/ekle)
// Veri JSON formatında passlanmalıdır.
$app->post('/api/musteri/ekle',function(Request $request,Response $response){
    $musteri_email = $request->getParsedBody()['musteri_email'];
    $musteri_sifre= $request->getParsedBody()['musteri_sifre'];
    $adi = $request->getParsedBody()['adi'];
    $soyadi = $request->getParsedBody()['soyadi'];
    $adres = $request->getParsedBody()['adres'];
    $ceptel = $request->getParsedBody()['ceptel'];

    $sql = "INSERT INTO musteriler (musteri_email,musteri_sifre,adi,soyadi,adres,ceptel) VALUES (:me,:ms,:ad,:sd,:ads,:ct)";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->bindParam(':me',$musteri_email);
        $stmt->bindParam(':ms',$musteri_sifre);
        $stmt->bindParam(':ad',$adi);
        $stmt->bindParam(':sd',$soyadi);
        $stmt->bindParam(':ads',$adres);
        $stmt->bindParam(':ct',$ceptel);
        $stmt->execute();

        // veri alındıysa
        echo '{"Bilgilendirme:" {"text": "Müşteri Eklendi"}}';
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//  Musteri Güncelle (/api/musteri/guncelle/{id})
// Veri JSON formatında passlanmalıdır.
$app->post('/api/musteri/guncelle/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $musteri_email = $request->getParsedBody()['musteri_email'];
    $musteri_sifre= $request->getParsedBody()['musteri_sifre'];
    $adi = $request->getParsedBody()['adi'];
    $soyadi = $request->getParsedBody()['soyadi'];
    $adres = $request->getParsedBody()['adres'];
    $ceptel = $request->getParsedBody()['ceptel'];

    $sql = "UPDATE musteriler SET
            musteri_email = :me,
            musteri_sifre = :ms,
            adi = :ad,
            soyadi = :sd,
            adres = :ads,
            ceptel = :ct
            WHERE musteri_id = $id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->bindParam(':me',$musteri_email);
        $stmt->bindParam(':ms',$musteri_sifre);
        $stmt->bindParam(':ad',$adi);
        $stmt->bindParam(':sd',$soyadi);
        $stmt->bindParam(':ads',$adres);
        $stmt->bindParam(':ct',$ceptel);
        $stmt->execute();

        // veri alındıysa
        echo '{"Bilgilendirme:" {"text": "Müşteri Güncellendi"}}';
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//  Müşteri Sil (/api//musteri/sil/{id})
$app->delete('/api/musteri/sil/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM musteriler WHERE musteri_id=$id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->execute();

        echo '{"Bilgilendirme:" {"text": "id:'.$id.' olan Müşteri Silindi"}}';

        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

include '../src/routes/personel.php';