<?php
// Yetki Kontrolu
if (!isset($_SESSION['admin_hazir']) || $_SESSION['admin_hazir'] !== true) {
    header('Location: /yonetim/giris');
    exit;
}

// Okundu İsaretle
if (isset($_GET['oku'])) {
    $id = intval($_GET['oku']);
    $db->prepare("UPDATE iletisim_mesajlari SET okundu_mu = 1 WHERE id = ?")->execute([$id]);
    header('Location: /yonetim/iletisim-mesajlari');
    exit;
}

// Sil
if (isset($_GET['sil'])) {
    $id = intval($_GET['sil']);
    $db->prepare("DELETE FROM iletisim_mesajlari WHERE id = ?")->execute([$id]);
    header('Location: /yonetim/iletisim-mesajlari');
    exit;
}

$mesajlar = $db->query("SELECT * FROM iletisim_mesajlari ORDER BY olusturma_tarihi DESC")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-800">İletişim Mesajları</h3>
            <p class="text-sm text-gray-500">Web sitenizden gelen iletişim formları.</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase font-bold text-xs">
                <tr>
                    <th class="px-6 py-3">Durum</th>
                    <th class="px-6 py-3">Gönderen</th>
                    <th class="px-6 py-3">Konu</th>
                    <th class="px-6 py-3">Tarih</th>
                    <th class="px-6 py-3 text-right">İşlem</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (count($mesajlar) == 0): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">Henüz mesaj yok.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($mesajlar as $m): ?>
                    <tr class="hover:bg-gray-50 <?php echo $m['okundu_mu'] == 0 ? 'bg-blue-50' : ''; ?>">
                        <td class="px-6 py-4">
                            <?php if ($m['okundu_mu'] == 0): ?>
                                <span
                                    class="inline-block px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded-full font-bold">Yeni</span>
                            <?php else: ?>
                                <span
                                    class="inline-block px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded-full">Okundu</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            <?php echo htmlspecialchars($m['ad_soyad']); ?><br>
                            <span class="font-normal text-gray-500 text-xs">
                                <?php echo htmlspecialchars($m['eposta']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php echo htmlspecialchars($m['konu']); ?>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <?php echo date('d.m.Y H:i', strtotime($m['olusturma_tarihi'])); ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="mesajGoster(<?php echo htmlspecialchars(json_encode($m)); ?>)"
                                class="text-blue-600 hover:text-blue-800 mr-3 font-bold">Oku</button>
                            <button onclick="onaylaVeSil('/yonetim/iletisim-mesajlari?sil=<?php echo $m['id']; ?>')"
                                class="text-red-500 hover:text-red-700"><i data-lucide="trash-2"
                                    class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function mesajGoster(m) {
        Swal.fire({
            title: m.konu,
            html: `
            <div class="text-left">
                <p class="mb-2"><strong>Gönderen:</strong> ${m.ad_soyad} (${m.eposta})</p>
                <p class="mb-2"><strong>Tarih:</strong> ${m.olusturma_tarihi}</p>
                <div class="bg-gray-50 p-3 rounded text-sm whitespace-pre-wrap border mt-3">${m.mesaj}</div>
            </div>
        `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: 'Okundu İşaretle',
            cancelButtonText: 'Kapat'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/yonetim/iletisim-mesajlari?oku=' + m.id;
            }
        });
    }
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Gelen Mesajlar';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>