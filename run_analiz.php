<?php
require 'config/database.php';
require 'app/Analizci.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $analizci = new Analizci($db, __DIR__);
    $sonuc = $analizci->calistir();
    echo "Analiz Tamamlandi:\n";
    print_r($sonuc);
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
