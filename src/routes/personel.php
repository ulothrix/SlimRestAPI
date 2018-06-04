<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Personel Login
$app->post('/api/yoneticiLogin',function(Request $request,Response $response){
    $email = $request->getParsedBody()['personel_email'];
    $sifre = $request->getParsedBody()['personel_sifre'];

    $sql = "SELECT * FROM personel WHERE personel_email='".$email."' AND personel_sifre='".$sifre."'";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->query($sql);
        // satır var mı yok mu bakabilmek için adetini check ediyoruz.
        $count = $stmt->rowCount();
        // ilgili koşullara uyan bilgi fetchleniyor.
        $personal = $stmt->fetch(PDO::FETCH_OBJ);

        // veri alındıysa
        if ($count>0) {
            echo $db->trCharConverterJson($personal);
        }else{
            echo json_encode(Array('isLogin' => '0'));
        }
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Tüm Personelleri Getir
$app->get('/api/personeller',function(Request $request,Response $response){
    $sql = "SELECT * FROM personel";
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

//  Tek bir personel bul (/api/personel/{id})
$app->get('/api/personel/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM personel WHERE personel_id=$id";
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

//  Personel Ekle (/api/personel/ekle)
// Veri JSON formatında passlanmalıdır.
$app->post('/api/personel/ekle',function(Request $request,Response $response){
    $adi = $request->getParsedBody()['adi'];
    $soyadi = $request->getParsedBody()['soyadi'];
    $gorevi = $request->getParsedBody()['gorevi'];
    $ceptel = $request->getParsedBody()['ceptel'];
    $personel_email = $request->getParsedBody()['personel_email'];
    $personel_sifre= $request->getParsedBody()['personel_sifre'];

    $sql = "INSERT INTO personel (adi,soyadi,gorevi,ceptel,personel_email,personel_sifre) VALUES (:ad,:sd,:ads,:ct,:me,:ms)";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->bindParam(':ad',$adi);
        $stmt->bindParam(':sd',$soyadi);
        $stmt->bindParam(':ads',$gorevi);
        $stmt->bindParam(':ct',$ceptel);
        $stmt->bindParam(':me',$personel_email);
        $stmt->bindParam(':ms',$personel_sifre);
        $stmt->execute();

        // veri alındıysa
        echo '{"Bilgilendirme:" {"text": "Personel Eklendi"}}';
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//  Personel Güncelle (/api/personel/guncelle/{id})
// Veri JSON formatında passlanmalıdır.
$app->post('/api/personel/guncelle/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $adi = $request->getParsedBody()['adi'];
    $soyadi = $request->getParsedBody()['soyadi'];
    $gorevi = $request->getParsedBody()['gorevi'];
    $ceptel = $request->getParsedBody()['ceptel'];
    $personel_email = $request->getParsedBody()['personel_email'];
    $personel_sifre= $request->getParsedBody()['personel_sifre'];

    $sql = "UPDATE personel SET
            adi = :ad,
            soyadi = :sd,
            gorevi = :ads,
            ceptel = :ct,
            personel_email = :me,
            personel_sifre = :ms
            WHERE personel_id = $id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->bindParam(':ad',$adi);
        $stmt->bindParam(':sd',$soyadi);
        $stmt->bindParam(':ads',$gorevi);
        $stmt->bindParam(':ct',$ceptel);
        $stmt->bindParam(':me',$personel_email);
        $stmt->bindParam(':ms',$personel_sifre);
        $stmt->execute();

        // veri alındıysa
        echo '{"Bilgilendirme:" {"text": "Personel Güncellendi"}}';

        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//  Personel Sil (/api/personel/sil/{id})
$app->delete('/api/personel/sil/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM personel WHERE personel_id=$id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->execute();

        echo '{"Bilgilendirme:" {"text": "id:'.$id.' olan Personel Silindi"}}';

        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

include '../src/routes/tasitlar.php';