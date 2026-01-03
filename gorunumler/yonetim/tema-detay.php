<?php
// Tema Icerik Editoru
$temaKodu = $_GET['tema_kodu'] ?? '';
if (!$temaKodu) {
    header('Location: /yonetim/temalar');
    exit;
}

// Analiz Et (Manuel Tetikleme)
if (isset($_GET['analiz_et'])) {
    require_once ROOT_DIR . '/app/TemaAnalizci.php';
    $analizci = new TemaAnalizci($db);
    $sayi = $analizci->temayiAnalizEt($temaKodu);
    header("Location: /yonetim/tema-detay?tema_kodu=$temaKodu&basari=1&msg=Analiz Tamamlandı. $sayi adet içerik bulundu.");
    exit;
}

// Guncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['icerik'] as $id => $deger) {
        $stmt = $db->prepare("UPDATE tema_icerikleri SET deger = ? WHERE id = ?");
        $stmt->execute([$deger, $id]);
    }
    header("Location: /yonetim/tema-detay?tema_kodu=$temaKodu&basari=1");
    exit;
}

// Icerikleri Cek
$icerikler = $db->prepare("SELECT * FROM tema_icerikleri WHERE tema_kodu = ? ORDER BY id ASC");
$icerikler->execute([$temaKodu]);
$liste = $icerikler->fetchAll();

// Tema Bilgisi
$temaBilgi = $db->prepare("SELECT * FROM temalar WHERE tema_kodu = ?");
$temaBilgi->execute([$temaKodu]);
$tema = $temaBilgi->fetch();

ob_start();
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-bold text-gray-700">Tema Düzenle:
            <?php echo $tema['ad']; ?>
        </h2>
        <p class="text-sm text-gray-400">
            <?php echo $temaKodu; ?>.htm
        </p>
    </div>
    <div class="flex gap-2">
        <a href="/yonetim/temalar" class="px-3 py-2 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 text-sm">Geri
            Dön</a>
        <a href="?tema_kodu=<?php echo $temaKodu; ?>&analiz_et=1"
            onclick="return confirm('Bu işlem sayfayı yeniden tarayacak ve mevcut değişiklikleri sıfırlayabilir. Devam edilsin mi?')"
            class="px-3 py-2 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 text-sm font-medium">
            <i data-lucide="scan" class="inline w-4 h-4 mr-1"></i> Yeniden Tara/Analiz Et
        </a>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
        <?php echo guvenli_html($_GET['msg']); ?>
    </div>
<?php endif; ?>

<?php if (empty($liste)): ?>
    <div class="bg-white p-8 text-center rounded-lg shadow-sm border border-gray-200">
        <div class="text-purple-500 mb-4 flex justify-center"><i data-lucide="search" class="w-12 h-12"></i></div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz içerik taranmamış</h3>
        <p class="text-gray-500 mb-6">Bu temanın resim ve yazılarını düzenleyebilmek için analiz etmelisiniz.</p>
        <a href="?tema_kodu=<?php echo $temaKodu; ?>&analiz_et=1"
            class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 font-medium">Şimdi Analiz Et</a>
    </div>
<?php else: ?>

    <form method="post" class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Dinamik İçerikler</h3>
            <button type="submit"
                class="px-6 py-2 bg-green-600 text-white font-bold rounded hover:bg-green-700 transition">Hepsini
                Kaydet</button>
        </div>

        <div class="divide-y divide-gray-100">
            <?php foreach ($liste as $ogeler): ?>
                <div class="p-4 grid grid-cols-12 gap-4 items-center hover:bg-gray-50">
                    <div class="col-span-12 md:col-span-3">
                        <span class="block text-sm font-medium text-gray-700">
                            <?php echo $ogeler['etiket']; ?>
                        </span>
                        <code class="text-xs text-gray-400"><?php echo $ogeler['anahtar']; ?></code>

                        <?php if ($ogeler['tur'] == 'resim'): ?>
                            <div class="mt-2 text-xs text-blue-500">Görsel URL</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-span-12 md:col-span-9">
                        <?php if ($ogeler['tur'] == 'yazi'): ?>
                            <textarea name="icerik[<?php echo $ogeler['id']; ?>]" rows="2"
                                class="w-full border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm"><?php echo guvenli_html($ogeler['deger']); ?></textarea>
                        <?php else: ?>
                            <div class="flex gap-2">
                                <input type="text" id="input_<?php echo $ogeler['id']; ?>"
                                    name="icerik[<?php echo $ogeler['id']; ?>]"
                                    value="<?php echo guvenli_html($ogeler['deger']); ?>"
                                    class="flex-1 border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm">

                                <button type="button" onclick="openUploadDialog(<?php echo $ogeler['id']; ?>)"
                                    class="bg-blue-100 text-blue-600 px-3 py-2 rounded hover:bg-blue-200 text-sm font-medium">
                                    <i data-lucide="upload" class="w-4 h-4"></i>
                                </button>

                                <?php if ($ogeler['deger']): ?>
                                    <a href="/<?php echo $ogeler['deger']; ?>" target="_blank"
                                        class="p-2 border rounded hover:bg-gray-100"><i data-lucide="external-link"
                                            class="w-4 h-4 text-gray-500"></i></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="p-6 border-t bg-gray-50 flex justify-end">
            <button type="submit"
                class="px-6 py-2 bg-green-600 text-white font-bold rounded hover:bg-green-700 transition">Hepsini
                Kaydet</button>
        </div>
    </form>
<?php endif; ?>

<script>
    let currentUploadId = null;
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*,video/*'; // Video destegi de eklendi
    fileInput.style.display = 'none';
    document.body.appendChild(fileInput);

    fileInput.addEventListener('change', async () => {
        if (!fileInput.files[0] || !currentUploadId) return;

        const formData = new FormData();
        formData.append('dosya', fileInput.files[0]);

        // Loading goster (opsiyonel)
        const btn = document.querySelector(`button[onclick="openUploadDialog(${currentUploadId})"]`);
        const originalContent = btn.innerHTML;
        btn.innerHTML = '...';
        btn.disabled = true;

        try {
            const res = await fetch('/app/EditorApi.php?action=medya_yukle', {
                method: 'POST',
                body: formData
            });

            const json = await res.json();

            if (json.success) {
                // Input degerini guncelle
                document.getElementById('input_' + currentUploadId).value = json.url;
                Swal.fire({
                    icon: 'success',
                    title: 'Yüklendi',
                    text: 'Dosya başarıyla yüklendi ve alan güncellendi.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Hata', text: json.error || 'Yükleme başarısız' });
            }
        } catch (e) {
            console.error(e);
            Swal.fire({ icon: 'error', title: 'Hata', text: 'Bağlantı hatası' });
        } finally {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            fileInput.value = ''; // Reset
        }
    });

    function openUploadDialog(id) {
        currentUploadId = id;
        fileInput.click();
    }
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Tema İçeriği Düzenle';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>