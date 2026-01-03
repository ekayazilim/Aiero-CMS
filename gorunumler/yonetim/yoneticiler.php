<?php
// Yetki Kontrolu
if (!isset($_SESSION['admin_hazir']) || $_SESSION['admin_hazir'] !== true) {
    header('Location: /yonetim/giris');
    exit;
}

if ($_SESSION['admin_rol'] !== 'admin') {
    header('Location: /yonetim/pano?hata=unauthorized');
    exit;
}

$mesaj = '';
$hata = '';

// 1. Yonetici Ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ekle'])) {
    $eposta = trim($_POST['eposta']);
    $sifre = $_POST['sifre'];
    $rol = $_POST['rol'] ?? 'editor';

    if (empty($eposta) || empty($sifre)) {
        $hata = "Lütfen e-posta ve şifre girin.";
    } else {
        // E-posta kontrol
        $kontrol = $db->prepare("SELECT COUNT(*) FROM kullanicilar WHERE eposta = ?");
        $kontrol->execute([$eposta]);
        if ($kontrol->fetchColumn() > 0) {
            $hata = "Bu e-posta adresi zaten kayıtlı.";
        } else {
            $hash = password_hash($sifre, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO kullanicilar (eposta, sifre_hash, rol) VALUES (?, ?, ?)");
            if ($stmt->execute([$eposta, $hash, $rol])) {
                header('Location: /yonetim/yoneticiler?basari=1');
                exit;
            } else {
                $hata = "Veritabanı hatası.";
            }
        }
    }
}

// 2. Yonetici Silme
if (isset($_GET['sil'])) {
    $silId = intval($_GET['sil']);

    // Kendini silmeyi engelle (Opsiyonel: Session'daki eposta ile kontrol edilebilir)
// En az 1 yonetici kalmasini sagla
    $sayi = $db->query("SELECT COUNT(*) FROM kullanicilar")->fetchColumn();

    if ($sayi <= 1) {
        $hata = "Sistemde en az 1 yönetici kalmalıdır.";
    } else {
        $stmt = $db->prepare("DELETE FROM kullanicilar
    WHERE id = ?");
        $stmt->execute([$silId]);
        header('Location: /yonetim/yoneticiler?basari=1');
        exit;
    }
}

// 3. Sifre Degistirme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sifre_degistir'])) {
    $id = intval($_POST['id']);
    $yeniSifre = $_POST['yeni_sifre'];

    if (!empty($yeniSifre)) {
        $hash = password_hash($yeniSifre, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE kullanicilar SET sifre_hash = ? WHERE id = ?");
        $stmt->execute([$hash, $id]);
        header('Location: /yonetim/yoneticiler?basari=1');
        exit;
    }
}

// Listeleme
$yoneticiler = $db->query("SELECT * FROM kullanicilar ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Liste -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4 border-b pb-2">Mevcut Yöneticiler</h3>

        <?php if ($hata): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>
                    <?php echo $hata; ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-500 text-sm border-b">
                        <th class="py-2">ID</th>
                        <th class="py-2">E-Posta</th>
                        <th class="py-2">Rol</th>
                        <th class="py-2">Kayıt Tarihi</th>
                        <th class="py-2 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    <?php foreach ($yoneticiler as $y): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 font-bold">#
                                <?php echo $y['id']; ?>
                            </td>
                            <td class="py-3">
                                <?php echo htmlspecialchars($y['eposta']); ?>
                            </td>
                            <td class="py-3">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    <?php echo strtoupper($y['rol']); ?>
                                </span>
                            </td>
                            <td class="py-3">
                                <?php echo date('d.m.Y', strtotime($y['olusturma_tarihi'])); ?>
                            </td>
                            <td class="py-3 text-right">
                                <button onclick="sifreDegistirModal(<?php echo $y['id']; ?>, '<?php echo $y['eposta']; ?>')"
                                    class="text-blue-500 hover:text-blue-700 mr-2" title="Şifre Değiştir">
                                    <i data-lucide="key" class="w-4 h-4 inline"></i>
                                </button>

                                <button onclick="onaylaVeSil('/yonetim/yoneticiler?sil=<?php echo $y['id']; ?>')"
                                    class="text-red-500 hover:text-red-700" title="Sil">
                                    <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ekleme Formu -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4 border-b pb-2">Yeni Yönetici Ekle</h3>
            <form action="" method="POST" class="space-y-4">
                <input type="hidden" name="ekle" value="1">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-Posta Adresi</label>
                    <input type="email" name="eposta" required
                        class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2 border">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Şifre</label>
                    <input type="password" name="sifre" required
                        class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2 border">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition font-medium text-sm">
                    <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i> Ekle
                </button>
            </form>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
            <h4 class="text-blue-800 font-bold text-sm mb-2"><i data-lucide="info" class="w-4 h-4 inline"></i> Bilgi
            </h4>
            <p class="text-blue-600 text-xs">
                Yöneticiler tam yetkiye sahiptir. Ayarları değiştirebilir, içerik girebilir ve temalara müdahale
                edebilirler.
            </p>
        </div>
    </div>
</div>

<script>
    function sifreDegistirModal(id, eposta) {
        Swal.fire({
            title: 'Şifre Değiştir',
            html: `
            <p class="text-sm text-gray-600 mb-4">${eposta} kullanıcısı için yeni şifre belirleyin.</p>
            <form id="pwForm" action="" method="POST">
                <input type="hidden" name="sifre_degistir" value="1">
                <input type="hidden" name="id" value="${id}">
                <input type="password" name="yeni_sifre" class="swal2-input" placeholder="Yeni Şifre" required>
            </form>
        `,
            showCancelButton: true,
            confirmButtonText: 'Güncelle',
            cancelButtonText: 'İptal',
            preConfirm: () => {
                const form = document.getElementById('pwForm');
                if (!form.yeni_sifre.value) {
                    Swal.showValidationMessage('Lütfen yeni şifre girin');
                    return false;
                }
                form.submit();
            }
        });
    }
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Yönetici Yönetimi';
require ROOT_DIR . '/gorunumler/yonetim/layout.php';
?>