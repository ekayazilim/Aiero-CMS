<?php

$ad_soyad = trim($_POST['name'] ?? '');
$eposta = trim($_POST['email'] ?? '');
$konu = trim($_POST['subject'] ?? '');
$mesaj = trim($_POST['message'] ?? '');

if (empty($ad_soyad) || empty($eposta) || empty($mesaj)) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=missing_fields');
    exit;
}

$stmt = $db->prepare("INSERT INTO iletisim_mesajlari (ad_soyad, eposta, konu, mesaj, okundu_mu) VALUES (?, ?, ?, ?, 0)");
$stmt->execute([$ad_soyad, $eposta, $konu, $mesaj]);

$smtpHost = $db->query("SELECT deger FROM ayarlar WHERE anahtar = 'smtp_host'")->fetchColumn();

if ($smtpHost) {
    $smtpPort = $db->query("SELECT deger FROM ayarlar WHERE anahtar = 'smtp_port'")->fetchColumn();
    $smtpUser = $db->query("SELECT deger FROM ayarlar WHERE anahtar = 'smtp_user'")->fetchColumn();
    $smtpPass = $db->query("SELECT deger FROM ayarlar WHERE anahtar = 'smtp_pass'")->fetchColumn();
    $smtpSecure = $db->query("SELECT deger FROM ayarlar WHERE anahtar = 'smtp_secure'")->fetchColumn();
    $siteBaslik = $db->query("SELECT deger FROM ayarlar WHERE anahtar = 'site_baslik'")->fetchColumn();
    $adminMail = $db->query("SELECT deger FROM ayarlar WHERE anahtar = 'iletisim_eposta'")->fetchColumn();


}

$referer = $_SERVER['HTTP_REFERER'];
if (strpos($referer, '?') !== false) {
    header('Location: ' . $referer . '&sent=1');
} else {
    header('Location: ' . $referer . '?sent=1');
}
exit;
