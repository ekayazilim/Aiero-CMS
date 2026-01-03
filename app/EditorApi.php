<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['admin_hazir']) || $_SESSION['admin_hazir'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Yetkisiz erişim']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    switch ($action) {
        case 'kaydet_taslak':
            $temaKodu = $input['tema_kodu'];
            $icerik = json_encode($input['icerik']);

            $stmt = $db->prepare("INSERT INTO tema_revizyonlari (tema_kodu, veri_json, olusturan_id, tur, tarih) VALUES (?, ?, ?, 'taslak', NOW())");
            $stmt->execute([$temaKodu, $icerik, $_SESSION['admin_id']]);

            echo json_encode(['success' => true, 'id' => $db->lastInsertId(), 'msg' => 'Taslak kaydedildi']);
            break;

        case 'yayinla':
            $temaKodu = $input['tema_kodu'];
            $icerik = $input['icerik'];


            $mevcut = $db->prepare("SELECT anahtar, deger, tur, etiket FROM tema_icerikleri WHERE tema_kodu = ?");
            $mevcut->execute([$temaKodu]);
            $mevcutVeri = $mevcut->fetchAll(PDO::FETCH_ASSOC);

            $mevcutJsonArr = [];
            foreach ($mevcutVeri as $satir) {
                $mevcutJsonArr[$satir['anahtar']] = $satir['deger'];
            }

            if (!empty($mevcutJsonArr)) {
                $stmtYedek = $db->prepare("INSERT INTO tema_revizyonlari (tema_kodu, veri_json, olusturan_id, tur, tarih) VALUES (?, ?, ?, 'yedek', NOW())");
                $stmtYedek->execute([$temaKodu, json_encode($mevcutJsonArr), $_SESSION['admin_id']]);
            }

            $stmtUpd = $db->prepare("UPDATE tema_icerikleri SET deger = ? WHERE tema_kodu = ? AND anahtar = ?");
            foreach ($icerik as $anahtar => $deger) {
                $stmtUpd->execute([$deger, $temaKodu, $anahtar]);
            }

            $stmtYayinda = $db->prepare("INSERT INTO tema_revizyonlari (tema_kodu, veri_json, olusturan_id, tur, tarih) VALUES (?, ?, ?, 'yayinda', NOW())");
            $stmtYayinda->execute([$temaKodu, json_encode($icerik), $_SESSION['admin_id']]);

            echo json_encode(['success' => true, 'msg' => 'Değişiklikler yayınlandı!']);
            break;

        case 'medya_yukle':

            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($action) {
        case 'revizyonlar':
            $temaKodu = $_GET['tema_kodu'];
            $stmt = $db->prepare("SELECT id, tur, tarih, olusturan_id FROM tema_revizyonlari WHERE tema_kodu = ? ORDER BY tarih DESC LIMIT 20");
            $stmt->execute([$temaKodu]);
            echo json_encode($stmt->fetchAll());
            break;

        case 'revizyon_getir':
            $id = $_GET['id'];
            $stmt = $db->prepare("SELECT veri_json FROM tema_revizyonlari WHERE id = ?");
            $stmt->execute([$id]);
            $kayit = $stmt->fetch();
            echo $kayit['veri_json'];
            break;
    }
}

if ($action == 'medya_yukle' && isset($_FILES['dosya'])) {
    $dosya = $_FILES['dosya'];
    $izinliler = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($dosya['type'], $izinliler)) {
        echo json_encode(['error' => 'Geçersiz dosya formatı']);
        exit;
    }

    $uzanti = pathinfo($dosya['name'], PATHINFO_EXTENSION);
    $yeniAd = uniqid('img_') . '.' . $uzanti;
    $hedef = __DIR__ . '/../assets/uploads/' . $yeniAd;

    if (!is_dir(__DIR__ . '/../assets/uploads')) {
        mkdir(__DIR__ . '/../assets/uploads', 0777, true);
    }

    if (move_uploaded_file($dosya['tmp_name'], $hedef)) {
        $stmt = $db->prepare("INSERT INTO medya (dosya_adi, yol, mime, boyut) VALUES (?, ?, ?, ?)");
        $yol = 'assets/uploads/' . $yeniAd;
        $stmt->execute([$dosya['name'], $yol, $dosya['type'], $dosya['size']]);

        echo json_encode(['success' => true, 'url' => '/' . $yol]);
    } else {
        echo json_encode(['error' => 'Dosya yüklenemedi']);
    }
    exit;
}
