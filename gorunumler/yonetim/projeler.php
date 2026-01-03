<?php
// Yetki Kontrolu
if (!isset($_SESSION['admin_hazir']) || $_SESSION['admin_hazir'] !== true) {
    header('Location: /yonetim/giris');
    exit;
}

$mesaj = '';
$hata = '';
$duzenleModu = false;
$duzenleVeri = [];

// EKLEME / GUNCELLEME ISLEMI
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik']);
    $musteri = trim($_POST['musteri']);
    $kategori = trim($_POST['kategori']);
    $ozet = trim($_POST['ozet']);
    $icerik = trim($_POST['icerik']);
    $resim = trim($_POST['resim']);

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $baslik)));

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $stmt = $db->prepare("UPDATE projeler SET baslik=?, slug=?, musteri=?, kategori=?, ozet=?, icerik=?, resim=? WHERE id=?");
        if ($stmt->execute([$baslik, $slug, $musteri, $kategori, $ozet, $icerik, $resim, $id])) {
            header('Location: /yonetim/projeler?basari=1');
            exit;
        }
    } else {
        $stmt = $db->prepare("INSERT INTO projeler (baslik, slug, musteri, kategori, ozet, icerik, resim) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$baslik, $slug, $musteri, $kategori, $ozet, $icerik, $resim])) {
            header('Location: /yonetim/projeler?basari=1');
            exit;
        }
    }
}

// SILME ISLEMI
if (isset($_GET['sil'])) {
    $id = intval($_GET['sil']);
    $db->prepare("DELETE FROM projeler WHERE id = ?")->execute([$id]);
    header('Location: /yonetim/projeler?basari=1');
    exit;
}

// DUZENLEME MODU
if (isset($_GET['duzenle'])) {
    $id = intval($_GET['duzenle']);
    $stmt = $db->prepare("SELECT * FROM projeler WHERE id = ?");
    $stmt->execute([$id]);
    $duzenleVeri = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($duzenleVeri) {
        $duzenleModu = true;
    }
}

$projeler = $db->query("SELECT * FROM projeler ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- LISTE -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Projeler / Portfolyo</h3>
            <?php if ($duzenleModu): ?>
                <a href="/yonetim/projeler" class="text-sm text-blue-600">Yeni Ekle Moduna Dön</a>
            <?php endif; ?>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-700 font-bold">
                    <tr>
                        <th class="px-6 py-3">Görsel</th>
                        <th class="px-6 py-3">Başlık / Kategori</th>
                        <th class="px-6 py-3 text-right">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($projeler as $p): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <img src="<?php echo htmlspecialchars($p['resim'] ?: 'assets/images/project/project1-1.png'); ?>"
                                    class="w-12 h-12 object-cover rounded shadow-sm">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">
                                    <?php echo htmlspecialchars($p['baslik']); ?>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <?php echo htmlspecialchars($p['kategori']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <a href="/yonetim/projeler?duzenle=<?php echo $p['id']; ?>"
                                    class="text-blue-600 font-bold mr-3">Düzenle</a>
                                <button onclick="onaylaVeSil('/yonetim/projeler?sil=<?php echo $p['id']; ?>')"
                                    class="text-red-500">Sil</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FORM -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold mb-4">
                <?php echo $duzenleModu ? 'Projeyi Düzenle' : 'Yeni Proje Ekle'; ?>
            </h3>
            <form action="" method="POST" class="space-y-4">
                <?php if ($duzenleModu): ?>
                    <input type="hidden" name="id" value="<?php echo $duzenleVeri['id']; ?>">
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Proje Adı</label>
                    <input type="text" name="baslik" required value="<?php echo $duzenleVeri['baslik'] ?? ''; ?>"
                        class="w-full border rounded px-3 py-2 text-sm border-gray-300">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Müşteri</label>
                        <input type="text" name="musteri" value="<?php echo $duzenleVeri['musteri'] ?? ''; ?>"
                            class="w-full border rounded px-3 py-2 text-sm border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <input type="text" name="kategori" value="<?php echo $duzenleVeri['kategori'] ?? ''; ?>"
                            class="w-full border rounded px-3 py-2 text-sm border-gray-300"
                            placeholder="Web Tasarım, AI vb.">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Görsel URL</label>
                    <div class="flex">
                        <input type="text" name="resim" value="<?php echo $duzenleVeri['resim'] ?? ''; ?>"
                            class="flex-1 border rounded-l px-3 py-2 text-sm border-gray-300 border-r-0">
                        <button type="button"
                            class="bg-gray-100 border border-gray-300 px-3 rounded-r text-sm">Seç</button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kısa Özet</label>
                    <textarea name="ozet" rows="2"
                        class="w-full border rounded px-3 py-2 text-sm border-gray-300"><?php echo $duzenleVeri['ozet'] ?? ''; ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">İçerik (HTML)</label>
                    <textarea name="icerik" rows="8"
                        class="w-full border rounded px-3 py-2 text-sm border-gray-300 font-mono"><?php echo $duzenleVeri['icerik'] ?? ''; ?></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded font-bold hover:bg-indigo-700 transition">
                    <?php echo $duzenleModu ? 'Kaydet' : 'Yayınla'; ?>
                </button>
            </form>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Proje Yönetimi';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>