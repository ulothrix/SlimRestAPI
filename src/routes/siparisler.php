<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Tüm Siparisleri Getir
$app->get('/api/siparisler',function(Request $request,Response $response){
    // En son eklenen en üstte
    $sql = "SELECT siparis_id,
                    musteriler.adi,
                    musteriler.soyadi,
                    urunler.urun_adi,
                    tasitlar.marka,
                    tasitlar.plaka,
                    personel.adi as personel_adi,
                    personel.soyadi as personel_Soyadi 
            FROM siparisler 
            INNER JOIN musteriler on siparisler.musteri_fk=musteriler.musteri_id 
            INNER JOIN tasitlar on siparisler.kargo_tasit_fk=tasitlar.id 
            INNER JOIN urunler on siparisler.urunler_fk=urunler.urun_id
            INNER JOIN personel on tasitlar.zimmetli_personel=personel.personel_id ORDER BY siparis_id DESC";
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

//  Tek bir sipariş bul (/api/siparis/{id})
$app->get('/api/siparis/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT musteriler.adi,
                    musteriler.soyadi,
                    urunler.urun_adi,
                    tasitlar.marka,
                    tasitlar.plaka,
                    personel.adi as personel_adi,
                    personel.soyadi as personel_Soyadi 
            FROM siparisler 
            INNER JOIN musteriler on siparisler.musteri_fk=musteriler.musteri_id 
            INNER JOIN tasitlar on siparisler.kargo_tasit_fk=tasitlar.id 
            INNER JOIN urunler on siparisler.urunler_fk=urunler.urun_id
            INNER JOIN personel on tasitlar.zimmetli_personel=personel.personel_id WHERE siparis_id=$id";
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


//  Kişiye ait siparişleri bul (/api/siparis/{musteri_id})
$app->get('/api/siparisler/musteri/{musteri_id}',function(Request $request,Response $response){
    $id = $request->getAttribute('musteri_id');
    $sql = "SELECT siparis_id,
                    musteriler.adi,
                    musteriler.soyadi,
                    urunler.urun_adi,
                    tasitlar.marka,
                    tasitlar.plaka,
                    personel.adi as personel_adi,
                    personel.soyadi as personel_Soyadi 
            FROM siparisler 
            INNER JOIN musteriler on siparisler.musteri_fk=musteriler.musteri_id 
            INNER JOIN tasitlar on siparisler.kargo_tasit_fk=tasitlar.id 
            INNER JOIN urunler on siparisler.urunler_fk=urunler.urun_id
            INNER JOIN personel on tasitlar.zimmetli_personel=personel.personel_id WHERE musteri_id=$id";
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

//  Sürücüye ait siparişleri bul (/api/siparis/{personel_id})
$app->get('/api/siparisler/personel/{personel_id}',function(Request $request,Response $response){
    $id = $request->getAttribute('personel_id');
    $sql = "SELECT siparis_id,
                    musteriler.adi,
                    musteriler.soyadi,
                    urunler.urun_adi,
                    tasitlar.marka,
                    tasitlar.plaka,
                    personel.adi as personel_adi,
                    personel.soyadi as personel_Soyadi 
            FROM siparisler 
            INNER JOIN musteriler on siparisler.musteri_fk=musteriler.musteri_id 
            INNER JOIN tasitlar on siparisler.kargo_tasit_fk=tasitlar.id 
            INNER JOIN urunler on siparisler.urunler_fk=urunler.urun_id
            INNER JOIN personel on tasitlar.zimmetli_personel=personel.personel_id WHERE personel.personel_id=$id";
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


// Sipariş Ekle (/api/siparis/ekle)
// Veri JSON formatında passlanmalıdır.
$app->post('/api/siparis/ekle',function(Request $request,Response $response){
    $musteri_fk = $request->getParsedBody()['musteri_fk'];
    $kargo_tasit_fk = $request->getParsedBody()['kargo_tasit_fk'];
    $urunler_fk = $request->getParsedBody()['urunler_fk'];

    $sql = "INSERT INTO siparisler (musteri_fk,kargo_tasit_fk,urunler_fk) VALUES (:musteri_fk,:kargo_tasit_fk,:urunler_fk)";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->bindParam(':musteri_fk',$musteri_fk);
        $stmt->bindParam(':kargo_tasit_fk',$kargo_tasit_fk);
        $stmt->bindParam(':urunler_fk',$urunler_fk);

        if($stmt->execute()){
            echo json_encode(Array('siparis_durumu' => '1'));
        }else{
            echo json_encode(Array('siparis_durumu' => '0'));
        }

        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//  Sipariş Güncelle (/api/siparis/guncelle/{id})
// Veri JSON formatında passlanmalıdır.
$app->post('/api/siparis/guncelle/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $musteri_fk = $request->getParsedBody()['musteri_fk'];
    $kargo_tasit_fk = $request->getParsedBody()['kargo_tasit_fk'];
    $urunler_fk = $request->getParsedBody()['urunler_fk'];

    $sql = "UPDATE siparisler SET
            musteri_fk = :musteri_fk,
            kargo_tasit_fk = :kargo_tasit_fk,
            urunler_fk = :urunler_fk
            WHERE siparis_id = $id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->bindParam(':musteri_fk',$musteri_fk);
        $stmt->bindParam(':kargo_tasit_fk',$kargo_tasit_fk);
        $stmt->bindParam(':urunler_fk',$urunler_fk);
        $stmt->execute();

        // veri alındıysa
        echo '{"Bilgilendirme:" {"text": "Sipariş Güncellendi"}}';
        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//  Sipariş Sil (/api/tasit/sil/{id})
$app->delete('/api/siparis/sil/{id}',function(Request $request,Response $response){
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM siparisler WHERE siparis_id=$id";
    try{
        // DB Object
        $db = new db();

        // Connect
        $dbConnected = $db->connect();

        $stmt = $dbConnected->prepare($sql);
        $stmt->execute();

        if($stmt->execute()){
            echo json_encode(Array('siparis_durumu' => 'Deleted'));
        }else{
            echo json_encode(Array('siparis_durumu' => 'Error'));
        }

        $dbConnected = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});