<?php
define('ROOT_DIR', __DIR__);
require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/app/Analizci.php';

echo "Kurulum Baslatiliyor...\n";
try {
    $analizci = new Analizci($db, ROOT_DIR);
    $sonuc = $analizci->calistir();
    echo "TAMAMLANDI!\n";
    echo "Sayfalar: " . $sonuc['sayfalar'] . "\n";
    echo "Temalar: " . $sonuc['temalar'] . "\n";
} catch (Exception $e) {
    echo "HATA: " . $e->getMessage() . "\n";
}
