<?php
// Islem: Tema Aktiflestir
if (isset($_GET['aktiflestir'])) {
    $yeniTema = $_GET['aktiflestir'];
    // Guvenlik: Bu tema var mi?
    $check = $db->prepare("SELECT count(*) FROM temalar WHERE tema_kodu = ?");
    $check->execute([$yeniTema]);
    if ($check->fetchColumn() > 0) {
        $upd = $db->prepare("UPDATE ayarlar SET deger = ? WHERE anahtar = 'aktif_ana_sayfa_tema'");
        $upd->execute([$yeniTema]);
        header('Location: /yonetim/temalar?basari=1');
        exit;
    }
}

// Temalari Cek
$temalar = $db->query("SELECT * FROM temalar ORDER BY tema_kodu ASC")->fetchAll();
$aktifTema = $db->query("SELECT deger FROM ayarlar WHERE anahtar='aktif_ana_sayfa_tema'")->fetchColumn();

ob_start();
?>

<?php
$anasayfalar = array_filter($temalar, function ($t) {
    return strpos($t['tema_kodu'], 'index') === 0; });
$altSayfalar = array_filter($temalar, function ($t) {
    return strpos($t['tema_kodu'], 'index') !== 0; });
?>

<div class="mb-6 border-b border-gray-200">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="themeTabs">
        <li class="mr-2">
            <button onclick="switchTab('anasayfa')" id="tab-anasayfa"
                class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active">Anasayfa
                Şablonları (<?php echo count($anasayfalar); ?>)</button>
        </li>
        <li class="mr-2">
            <button onclick="switchTab('altsayfa')" id="tab-altsayfa"
                class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg">Alt
                Sayfalar (<?php echo count($altSayfalar); ?>)</button>
        </li>
    </ul>
</div>

<!-- ANASAYFALAR -->
<div id="content-anasayfa" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php foreach ($anasayfalar as $tema): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden group hover:shadow-md transition">
            <div class="h-40 bg-gray-200 flex items-center justify-center relative overflow-hidden">
                <span class="text-gray-400 font-bold text-3xl">
                    <?php echo strtoupper(str_replace('index', '', $tema['tema_kodu']) ?: 'Ana'); ?>
                </span>
                <?php if ($aktifTema == $tema['tema_kodu']): ?>
                    <div class="absolute inset-0 bg-green-500 bg-opacity-20 flex items-center justify-center">
                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow">AKTİF</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-gray-800 mb-1"><?php echo $tema['ad']; ?></h3>
                <p class="text-xs text-gray-500 mb-4"><?php echo $tema['tema_kodu']; ?>.htm</p>

                <?php if ($aktifTema != $tema['tema_kodu']): ?>
                    <a href="?aktiflestir=<?php echo $tema['tema_kodu']; ?>"
                        class="block w-full text-center bg-white border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white py-2 rounded transition text-sm font-medium">Aktifleştir</a>
                <?php else: ?>
                    <button disabled
                        class="block w-full text-center bg-gray-100 text-gray-400 border border-gray-200 py-2 rounded cursor-not-allowed text-sm font-medium">Seçili</button>
                <?php endif; ?>

                <a href="/yonetim/tema-detay?tema_kodu=<?php echo $tema['tema_kodu']; ?>"
                    class="block w-full text-center mt-2 text-gray-500 hover:text-blue-600 text-xs font-medium">
                    <i data-lucide="edit-3" class="w-3 h-3 inline mr-1"></i> İçeriği Düzenle
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- ALT SAYFALAR -->
<div id="content-altsayfa" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php foreach ($altSayfalar as $tema): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden group hover:shadow-md transition">
            <div class="h-40 bg-gray-100 flex items-center justify-center relative overflow-hidden">
                <span class="text-gray-400 font-bold text-lg px-2 text-center">
                    <?php echo $tema['ad']; ?>
                </span>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-gray-800 mb-1"><?php echo $tema['ad']; ?></h3>
                <p class="text-xs text-gray-500 mb-4"><?php echo $tema['tema_kodu']; ?>.htm</p>

                <!-- Alt sayfalarda Aktiflestir YOK, sadece Duzenle ve Onizle -->
                <a href="/<?php echo $tema['tema_kodu']; ?>" target="_blank"
                    class="block w-full text-center bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 py-2 rounded transition text-sm font-medium">
                    <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i> Önizle
                </a>

                <a href="/yonetim/tema-detay?tema_kodu=<?php echo $tema['tema_kodu']; ?>"
                    class="block w-full text-center mt-2 text-gray-500 hover:text-blue-600 text-xs font-medium">
                    <i data-lucide="edit-3" class="w-3 h-3 inline mr-1"></i> İçeriği Düzenle
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function switchTab(tab) {
        document.getElementById('content-anasayfa').classList.add('hidden');
        document.getElementById('content-altsayfa').classList.add('hidden');
        document.getElementById('tab-anasayfa').className = 'inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg';
        document.getElementById('tab-altsayfa').className = 'inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg';

        document.getElementById('content-' + tab).classList.remove('hidden');
        document.getElementById('tab-' + tab).className = 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active';
    }
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Tema Seçimi';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>