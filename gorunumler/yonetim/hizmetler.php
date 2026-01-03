<?php
if (!isset($_SESSION['admin_hazir']) || $_SESSION['admin_hazir'] !== true) {
    header('Location: /yonetim/giris');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik']);
    $ozet = trim($_POST['ozet']);
    $ikon = trim($_POST['ikon']);
    $detay_link = trim($_POST['detay_link']);
    $sira = intval($_POST['sira']);

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $stmt = $db->prepare("UPDATE hizmetler SET baslik=?, ozet=?, ikon=?, detay_link=?, sira=? WHERE id=?");
        $stmt->execute([$baslik, $ozet, $ikon, $detay_link, $sira, $id]);
    } else {
        $stmt = $db->prepare("INSERT INTO hizmetler (baslik, ozet, ikon, detay_link, sira) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$baslik, $ozet, $ikon, $detay_link, $sira]);
    }
    header('Location: /yonetim/hizmetler?basari=1');
    exit;
}

if (isset($_GET['sil'])) {
    $db->prepare("DELETE FROM hizmetler WHERE id = ?")->execute([intval($_GET['sil'])]);
    header('Location: /yonetim/hizmetler?basari=1');
    exit;
}

$duzenleVeri = null;
if (isset($_GET['duzenle'])) {
    $stmt = $db->prepare("SELECT * FROM hizmetler WHERE id = ?");
    $stmt->execute([intval($_GET['duzenle'])]);
    $duzenleVeri = $stmt->fetch(PDO::FETCH_ASSOC);
}

$hizmetler = $db->query("SELECT * FROM hizmetler ORDER BY sira ASC, id DESC")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold">Hizmetlerimiz</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 font-bold">
                    <tr>
                        <th class="px-6 py-3">İkon</th>
                        <th class="px-6 py-3">Başlık</th>
                        <th class="px-6 py-3">Sıra</th>
                        <th class="px-6 py-3 text-right">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php foreach ($hizmetler as $h): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4"><i
                                    class="<?php echo htmlspecialchars($h['ikon']); ?> text-xl text-blue-600"></i></td>
                            <td class="px-6 py-4">
                                <div class="font-bold">
                                    <?php echo htmlspecialchars($h['baslik']); ?>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <?php echo htmlspecialchars(mb_substr($h['ozet'], 0, 50)); ?>...
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo $h['sira']; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="/yonetim/hizmetler?duzenle=<?php echo $h['id']; ?>"
                                    class="text-blue-600 mr-2">Düzenle</a>
                                <button onclick="onaylaVeSil('/yonetim/hizmetler?sil=<?php echo $h['id']; ?>')"
                                    class="text-red-500">Sil</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold mb-4">
                <?php echo $duzenleVeri ? 'Hizmeti Düzenle' : 'Yeni Hizmet Ekle'; ?>
            </h3>
            <form action="" method="post" class="space-y-4">
                <?php if ($duzenleVeri): ?> <input type="hidden" name="id" value="<?php echo $duzenleVeri['id']; ?>">
                <?php endif; ?>
                <div>
                    <label class="block text-sm font-medium mb-1">Başlık</label>
                    <input type="text" name="baslik" required value="<?php echo $duzenleVeri['baslik'] ?? ''; ?>"
                        class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">İkon Sınıfı (Örn: fontello icon-ai)</label>
                    <input type="text" name="ikon" value="<?php echo $duzenleVeri['ikon'] ?? ''; ?>"
                        class="w-full border rounded px-3 py-2 text-sm" placeholder="fontello icon-xxx">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Özet Açıklama</label>
                    <textarea name="ozet" rows="3"
                        class="w-full border rounded px-3 py-2 text-sm"><?php echo $duzenleVeri['ozet'] ?? ''; ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Detay Link (Opsiyonel)</label>
                    <input type="text" name="detay_link" value="<?php echo $duzenleVeri['detay_link'] ?? ''; ?>"
                        class="w-full border rounded px-3 py-2 text-sm" placeholder="/hizmet-detay/abc">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Görünüm Sırası</label>
                    <input type="number" name="sira" value="<?php echo $duzenleVeri['sira'] ?? '0'; ?>"
                        class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded">
                    <?php echo $duzenleVeri ? 'Güncelle' : 'Ekle'; ?>
                </button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Hizmet Yönetimi';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>