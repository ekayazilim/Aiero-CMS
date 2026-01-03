<?php
if ($_SESSION['admin_rol'] !== 'admin') {
    header('Location: /yonetim/pano?hata=unauthorized');
    exit;
}
// Ayarlari Guncelle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $anahtar => $deger) {
        // Her ayari guncelle
        // Guvenlik amaciyla sadece bilinen ayarlari guncelleyebiliriz ama 
        // admin paneli oldugu icin esnek birakiyorum simdilik.
        $stmt = $db->prepare("UPDATE ayarlar SET deger = ? WHERE anahtar = ?");
        $stmt->execute([$deger, $anahtar]);
    }
    header('Location: /yonetim/ayarlar?basari=1');
    exit;
}

// Tum ayarlari cek
$ayarlarListesi = $db->query("SELECT * FROM ayarlar")->fetchAll(PDO::FETCH_KEY_PAIR);

ob_start();
?>

<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Genel Site Ayarları</h3>
            <p class="text-sm text-gray-500">Sitenizin genel yapılandırma ayarlarını buradan yönetebilirsiniz.</p>
        </div>


        <!-- Tab Navigation -->
        <div class="mb-6 border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="settingsTabs">
                <li class="mr-2">
                    <button onclick="switchTab('genel')" id="tab-genel" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active">Genel</button>
                </li>
                <li class="mr-2">
                    <button onclick="switchTab('seo')" id="tab-seo" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg">SEO & Analiz</button>
                </li>
                <li class="mr-2">
                    <button onclick="switchTab('mail')" id="tab-mail" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg">SMTP Mail</button>
                </li>
                <li class="mr-2">
                    <button onclick="switchTab('bakim')" id="tab-bakim" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg">Bakım Modu</button>
                </li>
            </ul>
        </div>

        <form method="post" class="p-6">
            <!-- GENEL AYARLAR -->
            <div id="content-genel" class="space-y-6">
                <h3 class="font-bold text-gray-800 border-b pb-2">Genel Bilgiler</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Site Başlığı</label>
                        <input type="text" name="site_baslik" value="<?php echo guvenli_html($ayarlarListesi['site_baslik'] ?? ''); ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">İletişim E-posta</label>
                        <input type="email" name="iletisim_eposta" value="<?php echo guvenli_html($ayarlarListesi['iletisim_eposta'] ?? ''); ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Telefon</label>
                        <input type="text" name="iletisim_telefon" value="<?php echo guvenli_html($ayarlarListesi['iletisim_telefon'] ?? ''); ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Adres</label>
                        <input type="text" name="iletisim_adres" value="<?php echo guvenli_html($ayarlarListesi['iletisim_adres'] ?? ''); ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- SEO AYARLARI -->
            <div id="content-seo" class="hidden space-y-6">
                <h3 class="font-bold text-gray-800 border-b pb-2">SEO & Analiz</h3>
                <div>
                     <label class="block text-gray-700 font-medium mb-1">Site Açıklaması (Meta Description)</label>
                     <textarea name="site_aciklama" rows="3" class="w-full border border-gray-300 rounded px-3 py-2"><?php echo guvenli_html($ayarlarListesi['site_aciklama'] ?? ''); ?></textarea>
                </div>
                <div>
                     <label class="block text-gray-700 font-medium mb-1">Google Analytics Kodu (ID veya Script)</label>
                     <textarea name="google_analytics" rows="5" class="w-full border border-gray-300 rounded px-3 py-2 font-mono text-sm" placeholder="<script>...</script> veya GA-XXXXX"><?php echo guvenli_html($ayarlarListesi['google_analytics'] ?? ''); ?></textarea>
                     <p class="text-xs text-gray-500 mt-1">Head etiketleri arasına eklenir.</p>
                </div>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-sm"><strong>Otomatik Sitemap:</strong> <a href="/sitemap.xml" target="_blank" class="text-blue-600 hover:underline">/sitemap.xml</a> adresi üzerinden otomatik oluşturulur.</p>
                </div>
            </div>

            <!-- MAIL AYARLARI -->
            <div id="content-mail" class="hidden space-y-6">
                <h3 class="font-bold text-gray-800 border-b pb-2">SMTP Mail Ayarları</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">SMTP Host</label>
                        <input type="text" name="smtp_host" value="<?php echo guvenli_html($ayarlarListesi['smtp_host'] ?? ''); ?>" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="smtp.gmail.com">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">SMTP Port</label>
                        <input type="text" name="smtp_port" value="<?php echo guvenli_html($ayarlarListesi['smtp_port'] ?? '587'); ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">SMTP Kullanıcı Adı (E-posta)</label>
                        <input type="text" name="smtp_user" value="<?php echo guvenli_html($ayarlarListesi['smtp_user'] ?? ''); ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">SMTP Şifre</label>
                        <input type="password" name="smtp_pass" value="<?php echo guvenli_html($ayarlarListesi['smtp_pass'] ?? ''); ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                         <label class="block text-gray-700 font-medium mb-1">Protokol</label>
                         <select name="smtp_secure" class="w-full border border-gray-300 rounded px-3 py-2">
                             <option value="tls" <?php echo ($ayarlarListesi['smtp_secure'] ?? '') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                             <option value="ssl" <?php echo ($ayarlarListesi['smtp_secure'] ?? '') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                         </select>
                    </div>
                </div>
            </div>

            <!-- BAKIM MODU -->
            <div id="content-bakim" class="hidden space-y-6">
                <h3 class="font-bold text-gray-800 border-b pb-2">Bakım Modu</h3>
                <div class="flex items-center space-x-3 bg-yellow-50 p-4 rounded border border-yellow-200">
                    <input type="hidden" name="bakim_modu" value="0">
                    <input type="checkbox" name="bakim_modu" value="1" id="bakim_check" 
                        <?php echo ($ayarlarListesi['bakim_modu'] ?? '0') == '1' ? 'checked' : ''; ?>
                        class="w-5 h-5 text-blue-600 rounded">
                    <label for="bakim_check" class="font-bold text-gray-700 cursor-pointer">Bakım Modunu Aktifleştir</label>
                </div>
                <p class="text-sm text-gray-600">Bakım modu aktifken, yöneticiler siteyi görmeye devam edebilir ancak ziyaretçiler "Bakımdayız" mesajını görür.</p>
                
                <div>
                     <label class="block text-gray-700 font-medium mb-1">Bakım Başlığı</label>
                     <input type="text" name="bakim_baslik" value="<?php echo guvenli_html($ayarlarListesi['bakim_baslik'] ?? 'Bakımdayız'); ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                     <label class="block text-gray-700 font-medium mb-1">Bakım Mesajı</label>
                     <textarea name="bakim_aciklama" rows="3" class="w-full border border-gray-300 rounded px-3 py-2"><?php echo guvenli_html($ayarlarListesi['bakim_aciklama'] ?? 'Sitemizde kısa süreli bir bakım çalışması yapılmaktadır.'); ?></textarea>
                </div>
            </div>

            <div class="pt-6 border-t mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700 transition shadow-lg">
                    Ayarları Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tab) {
    // Hide all
    ['genel', 'seo', 'mail', 'bakim'].forEach(t => {
        document.getElementById('content-' + t).classList.add('hidden');
        document.getElementById('tab-' + t).className = 'inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg';
    });
    
    // Show active
    document.getElementById('content-' + tab).classList.remove('hidden');
    document.getElementById('tab-' + tab).className = 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active';
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Site Ayarları';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>