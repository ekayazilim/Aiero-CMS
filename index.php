<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_DIR', __DIR__);

require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/app/Kutuphane.php';

try {
    $stmt = $db->query("SELECT count(*) FROM temalar");
    if ($stmt->fetchColumn() == 0) {
        if (file_exists('install.php')) {
            header('Location: /install.php');
            exit;
        }
    }
} catch (PDOException $e) {
    if (file_exists('install.php')) {
        header('Location: /install.php');
        exit;
    }
    die("Sistem hatası: veritabanı tabloları bulunamadı.");
}

require_once ROOT_DIR . '/app/Router.php';

$url = isset($_GET['url']) ? $_GET['url'] : '/';

$router = new Router($db);
$router->resolve($url);
