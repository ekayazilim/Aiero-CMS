<?php
$hata = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eposta = $_POST['eposta'] ?? '';
    $sifre = $_POST['sifre'] ?? '';

    // DB Kontrol
    $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE eposta = ?");
    $stmt->execute([$eposta]);
    $user = $stmt->fetch();

    if ($user && password_verify($sifre, $user['sifre_hash'])) {
        $_SESSION['admin_hazir'] = true;
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_rol'] = $user['rol'];
        header('Location: /yonetim/pano');
        exit;
    } else {
        $hata = 'E-posta veya şifre hatalı!';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Giriş - Aiero CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Aiero CMS</h1>
            <p class="text-gray-500">Yönetim Paneli Girişi</p>
        </div>

        <?php if ($hata): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>
                    <?php echo $hata; ?>
                </p>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="eposta">E-posta</label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="eposta" name="eposta" type="email" placeholder="admin@admin.com" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="sifre">Şifre</label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                    id="sifre" name="sifre" type="password" placeholder="******" required>
            </div>
            <div class="flex items-center justify-between">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                    type="submit">
                    Giriş Yap
                </button>
            </div>
        </form>
        <p class="text-center text-gray-400 text-xs mt-4">Varsayılan: admin@admin.com / admin</p>
    </div>

</body>

</html>