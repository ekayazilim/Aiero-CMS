<?php
// Istatistikleri cek
$sayfaSayisi = $db->query("SELECT count(*) FROM sayfalar")->fetchColumn();
$temaSayisi = $db->query("SELECT count(*) FROM temalar")->fetchColumn();
$aktifTema = $db->query("SELECT deger FROM ayarlar WHERE anahtar='aktif_ana_sayfa_tema'")->fetchColumn();

// Output Buffer
ob_start();
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500">Toplam Sayfa</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-2">
                    <?php echo $sayfaSayisi; ?>
                </h3>
            </div>
            <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                <i data-lucide="file-text"></i>
            </div>
        </div>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500">Mevcut Temalar</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-2">
                    <?php echo $temaSayisi; ?>
                </h3>
            </div>
            <div class="p-3 bg-purple-50 rounded-lg text-purple-600">
                <i data-lucide="palette"></i>
            </div>
        </div>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500">Aktif Tema</p>
                <h3 class="text-xl font-bold text-green-600 mt-2">
                    <?php echo ucfirst($aktifTema); ?>
                </h3>
            </div>
            <div class="p-3 bg-green-50 rounded-lg text-green-600">
                <i data-lucide="check-circle"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Sistem Durumu ve Hızlı İşlemler</h3>
    </div>
    <div class="p-6">
        <p class="text-gray-600 mb-4">Sistem sorunsuz çalışıyor. Yeni sayfalar ekleyebilir veya temanızı
            değiştirebilirsiniz.</p>
        <div class="flex gap-3">
            <a href="/yonetim/temalar"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Tema Değiştir</a>
            <a href="/yonetim/sayfalar"
                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition">Sayfaları
                Yönet</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Pano';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>