<?php
if (!isset($_SESSION['admin_hazir']) || $_SESSION['admin_hazir'] !== true) {
    header('Location: /yonetim/giris');
    exit;
}

$mediaPath = ROOT_DIR . '/assets/images';
$mesaj = $_GET['basari'] ?? '';
$hata = $_GET['hata'] ?? '';
if (isset($_GET['silindi']))
    $mesaj = "Dosya silindi.";

// 1. Dosya Yukleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dosya'])) {
    if (!is_dir($mediaPath))
        mkdir($mediaPath, 0777, true);

    $dosya = $_FILES['dosya'];
    $ext = strtolower(pathinfo($dosya['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'ico'];

    if (!in_array($ext, $allowed)) {
        $hata = "Geçersiz dosya formatı!";
    } elseif ($dosya['size'] > 5 * 1024 * 1024) {
        $hata = "Dosya boyutu çok büyük! (Max 5MB)";
    } else {
        $targetName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $dosya['name']);
        if (move_uploaded_file($dosya['tmp_name'], $mediaPath . '/' . $targetName)) {
            header('Location: /yonetim/medya?basari=Dosya+yüklendi');
            exit;
        } else {
            $hata = "Dosya yüklenirken hata oluştu.";
        }
    }
}

// 2. Silme Islemi
if (isset($_GET['sil'])) {
    $fileToSil = ROOT_DIR . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $_GET['sil']);
    if (file_exists($fileToSil) && strpos(realpath($fileToSil), realpath($mediaPath)) === 0) {
        unlink($fileToSil);
        header('Location: /yonetim/medya?silindi=1');
        exit;
    }
}

$files = [];
if (file_exists($mediaPath)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($mediaPath));
    foreach ($it as $file) {
        if ($file->isFile() && preg_match('/\.(jpg|jpeg|png|gif|svg|webp|ico)$/i', $file->getFilename())) {
            $relPath = str_replace(ROOT_DIR . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $files[] = [
                'name' => $file->getFilename(),
                'path' => $relPath,
                'url' => '/' . str_replace('\\', '/', $relPath),
                'size' => round($file->getSize() / 1024, 2) . ' KB',
                'time' => date('d.m.Y H:i', $file->getMTime())
            ];
        }
    }
}

ob_start();
?>

<div class="mb-6 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
    <h3 class="font-bold mb-4 flex items-center">
        <i data-lucide="upload" class="w-5 h-5 mr-2 text-blue-600"></i>
        Dosya Yükle
    </h3>
    <form action="" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-4">
        <input type="file" name="dosya" required
            class="flex-1 border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition flex items-center justify-center">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Yükle
        </button>
    </form>
    <p class="text-xs text-gray-400 mt-2">İzin verilen formatlar: JPG, PNG, GIF, SVG, WEBP. Maksimum: 5MB</p>
</div>

<?php if ($mesaj): ?>
    <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 flex items-center">
        <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
        <?php echo htmlspecialchars($mesaj); ?>
    </div>
<?php endif; ?>

<?php if ($hata): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 flex items-center">
        <i data-lucide="alert-circle" class="w-5 h-5 mr-2"></i>
        <?php echo htmlspecialchars($hata); ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold">Mevcut Dosyalar</h3>
    </div>

    <div class="p-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <?php foreach ($files as $f): ?>
            <div class="group relative border rounded-lg overflow-hidden hover:shadow-md transition">
                <div class="aspect-square bg-gray-50 flex items-center justify-center p-2">
                    <img src="<?php echo $f['url']; ?>" class="max-w-full max-h-full object-contain">
                </div>
                <div class="p-2 text-[10px] bg-white border-t">
                    <div class="truncate font-bold" title="<?php echo $f['name']; ?>">
                        <?php echo $f['name']; ?>
                    </div>
                    <div class="text-gray-400">
                        <?php echo $f['size']; ?>
                    </div>
                </div>
                <div
                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center space-x-2">
                    <a href="<?php echo $f['url']; ?>" target="_blank"
                        class="bg-white p-1 rounded text-gray-700 hover:text-blue-600"><i data-lucide="eye"
                            class="w-4 h-4"></i></a>
                    <button onclick="onaylaVeSil('/yonetim/medya?sil=<?php echo urlencode($f['path']); ?>')"
                        class="bg-white p-1 rounded text-red-500 hover:text-red-700"><i data-lucide="trash-2"
                            class="w-4 h-4"></i></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Medya Kütüphanesi';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>