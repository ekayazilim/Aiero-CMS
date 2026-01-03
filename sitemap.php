<?php
require_once 'config/database.php';

header("Content-Type: application/xml; charset=utf-8");

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

echo '<url>';
echo '<loc>' . $baseUrl . '/</loc>';
echo '<changefreq>daily</changefreq>';
echo '<priority>1.0</priority>';
echo '</url>';

$sayfalar = $db->query("SELECT slug, guncelleme_tarihi FROM sayfalar WHERE durum = 1 AND slug != 'anasayfa'")->fetchAll();

foreach ($sayfalar as $sayfa) {
    echo '<url>';
    echo '<loc>' . $baseUrl . '/' . $sayfa['slug'] . '</loc>';
    echo '<lastmod>' . date('Y-m-d', strtotime($sayfa['guncelleme_tarihi'])) . '</lastmod>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.8</priority>';
    echo '</url>';
}


echo '</urlset>';
