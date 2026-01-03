<?php
// Layout Wrapper
// Icerigi tampona alip buraya basacagiz veya include edecegiz
// Basitlik adina: Header ve Sidebar ciktisi veren fonksiyonlar veya include'lar
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli - Aiero CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script> <!-- Icons -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .swal2-popup {
            font-size: 0.9rem !important;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="min-h-screen flex text-gray-800 antialiased font-sans overflow-hidden">
        <!-- Sidebar -->
        <aside class="relative bg-white w-64 hidden xl:block shadow-xl flex-shrink-0 z-10">
            <div class="h-20 flex items-center justify-center border-b px-4">
                <h2 class="text-xl font-bold text-blue-600">Aiero Panel</h2>
            </div>
            <nav class="p-4 space-y-2 overflow-y-auto h-[calc(100vh-5rem)]">
                <a href="/yonetim/pano" class="flex items-center px-4 py-3 bg-gray-50 text-blue-600 rounded-lg group">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    <span class="font-medium">Pano</span>
                </a>
                <a href="/yonetim/temalar"
                    class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition">
                    <i data-lucide="palette" class="w-5 h-5 mr-3"></i>
                    <span class="font-medium">Temalar</span>
                </a>
                <a href="/yonetim/sayfalar"
                    class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition">
                    <i data-lucide="file-text" class="w-5 h-5 mr-3"></i>
                    <span class="font-medium">Sayfalar</span>
                </a>
                <a href="/yonetim/bolumler"
                    class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition">
                    <i data-lucide="layout" class="w-5 h-5 mr-3"></i>
                    <span class="font-medium">Sayfa Bölümleri</span>
                </a>
                <a href="/yonetim/hizmetler"
                    class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition">
                    <i data-lucide="cpu" class="w-5 h-5 mr-3"></i>
                    <span class="font-medium">Hizmetlerimiz</span>
                </a>
                <a href="/yonetim/blog"
                    class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition">
                    <i data-lucide="pen-tool" class="w-5 h-5 mr-3"></i>
                    <span class="font-medium">Blog & Haberler</span>
                </a>
                <a href="/yonetim/medya"
                    class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition">
                    <i data-lucide="image" class="w-5 h-5 mr-3"></i>
                    <span class="font-medium">Medya Kütüphanesi</span>
                </a>
                <a href="/yonetim/projeler"
                    class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition">
                    <i data-lucide="folder-kanban" class="w-5 h-5 mr-3"></i>
                    <span class="font-medium">Projeler</span>
                </a>
                <a href="/yonetim/iletisim-mesajlari"
                    class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition">
                    <i data-lucide="mail" class="w-5 h-5 mr-3"></i>
                    <span class="font-medium">Mesajlar</span>
                </a>
                <?php if ($_SESSION['admin_rol'] === 'admin'): ?>
                    <a href="/yonetim/ayarlar"
                        class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition">
                        <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium">Ayarlar</span>
                    </a>
                    <a href="/yonetim/yoneticiler"
                        class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition">
                        <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium">Yöneticiler</span>
                    </a>
                <?php endif; ?>
                <div class="pt-4 border-t mt-4">
                    <a href="/yonetim/cikis"
                        class="flex items-center px-4 py-3 text-red-500 hover:bg-red-50 hover:text-red-700 rounded-lg transition">
                        <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium">Çıkış Yap</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Content -->
        <div class="flex-1 flex flex-col h-screen overflow-y-auto relative">
            <!-- Mobile Header -->
            <header class="h-16 bg-white border-b flex items-center justify-between px-6 xl:hidden">
                <span class="font-bold">Aiero CMS</span>
                <button class="text-gray-500"><i data-lucide="menu"></i></button>
            </header>

            <main class="p-6 md:p-8 flex-1">
                <?php if (isset($pageTitle)): ?>
                    <div class="mb-6 flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-800">
                            <?php echo $pageTitle; ?>
                        </h1>
                        <?php if (isset($headerAction)):
                            echo $headerAction;
                        endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Icerik Buraya -->
                <?php echo $content; ?>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Global SweetAlert Fonksiyonlari
        function onaylaVeSil(url) {
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu işlem geri alınamaz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }

        // URL parametresinde mesaj varsa goster
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('basari')) {
            Swal.fire({ position: 'top-end', icon: 'success', title: 'İşlem Başarılı', showConfirmButton: false, timer: 1500 });
        }
    </script>
</body>

</html>