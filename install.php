<?php
define('ROOT_DIR', __DIR__);
require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/app/Analizci.php';

$mesaj = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $analizci = new Analizci($db, ROOT_DIR);
        $sonuc = $analizci->calistir();

        $mesaj = "Kurulum Başarılı! <br>Taranan Sayfalar: " . $sonuc['sayfalar'] . "<br>Bulunan Temalar: " . $sonuc['temalar'];
        $mesaj .= "<br><br><a href='/yonetim/giris' class='btn'>Yönetim Paneline Git</a>";

    } catch (Exception $e) {
        $mesaj = "Hata: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Aiero CMS Kurulum</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #2563eb;
        }

        .btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .info {
            margin: 20px 0;
            color: #4b5563;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Aiero CMS</h1>
        <p class="info">HTML Şablon Paketini İçe Aktar ve Sistemi Kur.</p>

        <?php if ($mesaj): ?>
            <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                <?php echo $mesaj; ?>
            </div>
        <?php else: ?>
            <form method="post">
                <button type="submit" class="btn">Kurulumu Başlat & Tara</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>