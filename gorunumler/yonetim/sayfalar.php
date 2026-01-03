<?php
// Sayfalari Cek
$sayfalar = $db->query("SELECT * FROM sayfalar ORDER BY id ASC")->fetchAll();

ob_start();
?>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-medium">
            <tr>
                <th class="px-6 py-4">Sayfa Başlığı</th>
                <th class="px-6 py-4">Slug (URL)</th>
                <th class="px-6 py-4">Durum</th>
                <th class="px-6 py-4 text-right">İşlemler</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach ($sayfalar as $sayfa): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">
                        <?php echo $sayfa['baslik']; ?>
                        <span class="block text-xs text-gray-400 mt-0.5">
                            <?php echo $sayfa['sayfa_kodu']; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <a href="/<?php echo $sayfa['slug'] == 'anasayfa' ? '' : $sayfa['slug']; ?>" target="_blank"
                            class="hover:underline hover:text-blue-600 flex items-center">
                            /
                            <?php echo $sayfa['slug']; ?>
                            <i data-lucide="external-link" class="w-3 h-3 ml-1"></i>
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($sayfa['durum']): ?>
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold">Aktif</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">Pasif</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="/yonetim/sayfa-duzenle?id=<?php echo $sayfa['id']; ?>"
                            class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded text-sm font-medium transition">
                            <i data-lucide="edit-2" class="w-4 h-4 mr-1.5"></i> Düzenle
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Sayfa Yönetimi';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>