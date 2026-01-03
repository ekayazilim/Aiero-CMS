<?php
$sayfa_kodu = $_GET['sayfa_kodu'] ?? '';
if (!$sayfa_kodu) {
    header('Location: /yonetim/sayfalar');
    exit;
}

// Yeni bolum ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ekle'])) {
    $baslik = $_POST['baslik'];
    $bolum_kodu = $_POST['bolum_kodu'];
    $veri_json = $_POST['veri_json'];

    $stmt = $db->prepare("INSERT INTO sayfa_bolumleri (sayfa_kodu, bolum_kodu, baslik, veri_json) VALUES (?, ?, ?, ?)");
    $stmt->execute([$sayfa_kodu, $bolum_kodu, $baslik, $veri_json]);
    header("Location: /yonetim/bolumler?sayfa_kodu=$sayfa_kodu&basari=1");
    exit;
}

// Bolum silme
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $db->prepare("DELETE FROM sayfa_bolumleri WHERE id = ?")->execute([$id]);
    header("Location: /yonetim/bolumler?sayfa_kodu=$sayfa_kodu&basari=1");
    exit;
}

$bolumler = $db->prepare("SELECT * FROM sayfa_bolumleri WHERE sayfa_kodu = ? ORDER BY sira ASC");
$bolumler->execute([$sayfa_kodu]);
$liste = $bolumler->fetchAll();

ob_start();
?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-xl font-bold text-gray-700">Sayfa:
        <?php echo $sayfa_kodu; ?>
    </h2>
    <a href="/yonetim/sayfalar" class="text-gray-500 hover:underline">Geri Dön</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Ekleme Formu -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 h-fit">
        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Yeni Bölüm Ekle</h3>
        <form method="post">
            <input type="hidden" name="ekle" value="1">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Bölüm Başlığı</label>
                <input type="text" name="baslik" class="w-full border rounded px-3 py-2" required
                    placeholder="Örn: Hero Slider">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Bölüm Kodu (Unique ID)</label>
                <input type="text" name="bolum_kodu" class="w-full border rounded px-3 py-2" required
                    placeholder="Örn: hero_section">
                <p class="text-xs text-gray-400 mt-1">HTML içinde bu ID ile eşleşecek.</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Veri (JSON)</label>
                <textarea name="veri_json" rows="5"
                    class="w-full border rounded px-3 py-2 font-mono text-sm">{"baslik": "Merhaba", "icerik": "..."}</textarea>
            </div>
            <button type="submit"
                class="w-full bg-green-600 text-white font-bold py-2 rounded hover:bg-green-700">Ekle</button>
        </form>
    </div>

    <!-- Liste -->
    <div class="lg:col-span-2 space-y-4">
        <?php foreach ($liste as $bolum): ?>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex justify-between items-start group">
                <div>
                    <h4 class="font-bold text-gray-800">
                        <?php echo $bolum['baslik']; ?>
                    </h4>
                    <code
                        class="text-xs bg-gray-100 px-2 py-1 rounded text-red-500">#<?php echo $bolum['bolum_kodu']; ?></code>
                    <pre
                        class="mt-2 text-xs text-gray-500 bg-gray-50 p-2 rounded overflow-x-auto max-w-lg"><?php echo $bolum['veri_json']; ?></pre>
                </div>
                <div class="flex flex-col gap-2">
                    <button class="text-xs bg-gray-100 hover:bg-gray-200 px-2 py-1 rounded">Düzenle</button>
                    <a href="javascript:onaylaVeSil('/yonetim/bolumler?sayfa_kodu=<?php echo $sayfa_kodu; ?>&sil=<?php echo $bolum['id']; ?>')"
                        class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-2 py-1 rounded text-center">Sil</a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($liste)): ?>
            <div class="p-8 text-center text-gray-400 bg-gray-50 rounded border border-dashed">
                Bu sayfa için henüz bölüm tanımlanmamış.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Bölüm Yönetimi';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>