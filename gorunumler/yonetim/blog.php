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
    $ozet = trim($_POST['ozet']);
    $icerik = trim($_POST['icerik']);
    $resim = trim($_POST['resim']); // URL olarak

    // Slug olustur (basit)
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $baslik)));

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Guncelle
        $id = intval($_POST['id']);
        $stmt = $db->prepare("UPDATE blog_yazilari SET baslik=?, slug=?, ozet=?, icerik=?, resim=? WHERE id=?");
        if ($stmt->execute([$baslik, $slug, $ozet, $icerik, $resim, $id])) {
            header('Location: /yonetim/blog?basari=1');
            exit;
        }
    } else {
        // Ekle
        $stmt = $db->prepare("INSERT INTO blog_yazilari (baslik, slug, ozet, icerik, resim) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$baslik, $slug, $ozet, $icerik, $resim])) {
            header('Location: /yonetim/blog?basari=1');
            exit;
        }
    }
}

// SILME ISLEMI
if (isset($_GET['sil'])) {
    $id = intval($_GET['sil']);
    $db->prepare("DELETE FROM blog_yazilari WHERE id = ?")->execute([$id]);
    header('Location: /yonetim/blog?basari=1');
    exit;
}

// DUZENLEME MODU
if (isset($_GET['duzenle'])) {
    $id = intval($_GET['duzenle']);
    $duzenleVeri = $db->prepare("SELECT * FROM blog_yazilari WHERE id = ?")->execute([$id]);
    $duzenleVeri = $db->prepare("SELECT * FROM blog_yazilari WHERE id = ?");
    $duzenleVeri->execute([$id]);
    $duzenleVeri = $duzenleVeri->fetch(PDO::FETCH_ASSOC);
    if ($duzenleVeri) {
        $duzenleModu = true;
    }
}

$yazilar = $db->query("SELECT * FROM blog_yazilari ORDER BY olusturma_tarihi DESC")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- LISTE -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Blog Yazıları</h3>
            <?php if ($duzenleModu): ?>
                <a href="/yonetim/blog" class="text-sm text-blue-600 hover:underline">Yeni Ekle Moduna Dön</a>
            <?php endif; ?>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-3">Resim</th>
                        <th class="px-6 py-3">Başlık</th>
                        <th class="px-6 py-3">Tarih</th>
                        <th class="px-6 py-3 text-right">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($yazilar as $y): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <?php if ($y['resim']): ?>
                                    <img src="<?php echo htmlspecialchars($y['resim']); ?>"
                                        class="w-12 h-12 object-cover rounded">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-xs">No Img
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <?php echo htmlspecialchars($y['baslik']); ?>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <?php echo date('d.m.Y', strtotime($y['olusturma_tarihi'])); ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="/yonetim/blog?duzenle=<?php echo $y['id']; ?>"
                                    class="text-blue-600 hover:text-blue-800 mr-3 font-bold">Düzenle</a>
                                <button onclick="onaylaVeSil('/yonetim/blog?sil=<?php echo $y['id']; ?>')"
                                    class="text-red-500 hover:text-red-700">Sil</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FORM -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4 border-b pb-2">
                <?php echo $duzenleModu ? 'Yazıyı Düzenle' : 'Yeni Blog Yazısı'; ?>
            </h3>

            <form action="" method="POST" class="space-y-4">
                <?php if ($duzenleModu): ?>
                    <input type="hidden" name="id" value="<?php echo $duzenleVeri['id']; ?>">
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Başlık</label>
                    <input type="text" name="baslik" required value="<?php echo $duzenleVeri['baslik'] ?? ''; ?>"
                        class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2 border">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kapak Resmi URL</label>
                    <div class="flex">
                        <input type="text" name="resim" id="resimInput"
                            value="<?php echo $duzenleVeri['resim'] ?? ''; ?>"
                            class="flex-1 rounded-l border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2 border">
                        <button type="button"
                            class="bg-gray-100 border border-l-0 border-gray-300 px-3 rounded-r hover:bg-gray-200 text-sm">Seç</button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Özet (Kısa Açıklama)</label>
                    <textarea name="ozet" rows="3"
                        class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2 border"><?php echo $duzenleVeri['ozet'] ?? ''; ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">İçerik (HTML)</label>
                    <textarea name="icerik" rows="10"
                        class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2 border font-mono"><?php echo $duzenleVeri['icerik'] ?? ''; ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">HTML etiketleri kullanabilirsiniz.</p>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition font-medium">
                    <?php echo $duzenleModu ? 'Güncelle' : 'Yayınla'; ?>
                </button>
            </form>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Blog Yönetimi';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>