<?php
$id = $_GET['id'] ?? 0;
$stmt = $db->prepare("SELECT * FROM sayfalar WHERE id = ?");
$stmt->execute([$id]);
$sayfa = $stmt->fetch();

if (!$sayfa) {
    header('Location: /yonetim/sayfalar');
    exit;
}

// Guncelleme Islemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = $_POST['baslik'];
    $slug = $_POST['slug'];
    $meta_baslik = $_POST['meta_baslik'];
    $meta_aciklama = $_POST['meta_aciklama'];
    $durum = isset($_POST['durum']) ? 1 : 0;

    // Slug cakisma kontrolu (kendisi haric)
    $check = $db->prepare("SELECT count(*) FROM sayfalar WHERE slug = ? AND id != ?");
    $check->execute([$slug, $id]);
    if ($check->fetchColumn() > 0) {
        $hata = "Bu URL (slug) zaten kullanılıyor.";
    } else {
        $upd = $db->prepare("UPDATE sayfalar SET baslik=?, slug=?, meta_baslik=?, meta_aciklama=?, durum=? WHERE id=?");
        $upd->execute([$baslik, $slug, $meta_baslik, $meta_aciklama, $durum, $id]);
        header("Location: /yonetim/sayfa-duzenle?id=$id&basari=1");
        exit;
    }
}

ob_start();
?>

<form method="post" class="max-w-4xl">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sol Kolon: Temel Bilgiler -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Sayfa İçeriği</h3>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Sayfa Başlığı</label>
                    <input type="text" name="baslik" value="<?php echo guvenli_html($sayfa['baslik']); ?>"
                        class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">URL (Slug)</label>
                    <div class="flex">
                        <span
                            class="inline-flex items-center px-3 rounded-l border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-sm">/</span>
                        <input type="text" name="slug" value="<?php echo guvenli_html($sayfa['slug']); ?>"
                            class="flex-1 border border-gray-300 rounded-r px-3 py-2 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500"
                            required>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">SEO Ayarları</h3>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Meta Başlık</label>
                    <input type="text" name="meta_baslik" value="<?php echo guvenli_html($sayfa['meta_baslik']); ?>"
                        class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Tarayıcı sekmesinde görünen başlık.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Meta Açıklama</label>
                    <textarea name="meta_aciklama" rows="3"
                        class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500"><?php echo guvenli_html($sayfa['meta_aciklama']); ?></textarea>
                    <p class="text-xs text-gray-400 mt-1">Arama motorlarında görünen kısa açıklama.</p>
                </div>
            </div>
        </div>

        <!-- Sag Kolon: Durum ve Eylemler -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Yayın Durumu</h3>

                <label class="flex items-center cursor-pointer mb-4">
                    <input type="checkbox" name="durum" class="sr-only peer" <?php echo $sayfa['durum'] ? 'checked' : ''; ?>>
                    <div
                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                    </div>
                    <span class="ms-3 text-sm font-medium text-gray-900">Aktif / Yayında</span>
                </label>

                <div class="pt-4 border-t flex flex-col gap-2">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                        Kaydet
                    </button>
                    <a href="/yonetim/sayfalar"
                        class="w-full block text-center bg-gray-100 text-gray-600 py-2 px-4 rounded hover:bg-gray-200 transition">
                        İptal
                    </a>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <h4 class="font-bold text-blue-800 mb-2">Bölüm Yönetimi</h4>
                <p class="text-sm text-blue-600 mb-3">Bu sayfanın bölümlerini (slider, hakkımızda yazısı vb.) düzenlemek
                    için:</p>
                <a href="/yonetim/bolumler?sayfa_kodu=<?php echo $sayfa['sayfa_kodu']; ?>"
                    class="block w-full text-center bg-white border border-blue-200 text-blue-600 py-2 rounded hover:bg-blue-600 hover:text-white transition">
                    Bölümleri Yönet <i data-lucide="arrow-right" class="w-4 h-4 inline ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</form>

<?php if (isset($hata)): ?>
    <script>
        Swal.fire({ icon: 'error', title: 'Hata', text: '<?php echo $hata; ?>' });
    </script>
<?php endif; ?>

<?php
$content = ob_get_clean();
$pageTitle = 'Sayfa Düzenle: ' . guvenli_html($sayfa['baslik']);
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>