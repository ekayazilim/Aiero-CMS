<?php

class SayfaRender
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function renderPage($sayfa)
    {
        $temaKodu = $sayfa['sayfa_kodu'];
        $this->renderThemeFile($temaKodu);
    }

    private function renderThemeFile($temaKodu)
    {
        $isAdmin = isset($_SESSION['admin_hazir']) && $_SESSION['admin_hazir'] === true;

        if (!$isAdmin) {
            $bakim = $this->db->query("SELECT deger FROM ayarlar WHERE anahtar = 'bakim_modu'")->fetchColumn();
            if ($bakim == '1') {
                $baslik = $this->db->query("SELECT deger FROM ayarlar WHERE anahtar = 'bakim_baslik'")->fetchColumn() ?: 'Bakımdayız';
                $aciklama = $this->db->query("SELECT deger FROM ayarlar WHERE anahtar = 'bakim_aciklama'")->fetchColumn() ?: 'Sitemiz şu anda bakımdadır.';

                header('HTTP/1.1 503 Service Unavailable');
                echo '<!DOCTYPE html><html><head><title>' . $baslik . '</title><meta charset="utf-8">';
                echo '<style>body{display:flex;justify-content:center;align-items:center;height:100vh;margin:0;font-family:sans-serif;background:#f3f4f6;color:#374151}.container{text-align:center;background:white;padding:2rem;border-radius:1rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1)}</style>';
                echo '</head><body><div class="container"><h1>' . $baslik . '</h1><p>' . $aciklama . '</p></div></body></html>';
                return;
            }
        }

        $dosya = ROOT_DIR . '/gorunumler/tema/' . $temaKodu . '.php';

        if (!file_exists($dosya)) {
            $this->render404();
            return;
        }

        $stmt = $this->db->prepare("SELECT anahtar, deger, tur FROM tema_icerikleri WHERE tema_kodu = ?");
        $stmt->execute([$temaKodu]);
        $veriler = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $veriHaritasi = [];
        $veriTurleri = [];
        foreach ($veriler as $v) {
            $veriHaritasi[$v['anahtar']] = $v['deger'];
            $veriTurleri[$v['anahtar']] = $v['tur'];
        }

        ob_start();
        include $dosya;
        $html = ob_get_clean();

        $isAdmin = isset($_SESSION['admin_hazir']) && $_SESSION['admin_hazir'] === true;

        foreach ($veriHaritasi as $anahtar => $deger) {
            $placeholder = "{{" . $anahtar . "}}";

            if ($isAdmin) {
                if ($veriTurleri[$anahtar] == 'resim') {
                    $html = str_replace($placeholder, $deger . '" data-editable="true" data-key="' . $anahtar, $html);
                } else {
                    $html = str_replace($placeholder, '<span data-editable="true" data-key="' . $anahtar . '">' . $deger . '</span>', $html);
                }
            } else {
                $html = str_replace($placeholder, $deger, $html);
            }
        }

        if ($temaKodu === 'blog') {
            $yazilar = $this->db->query("SELECT * FROM blog_yazilari ORDER BY olusturma_tarihi DESC")->fetchAll(PDO::FETCH_ASSOC);
            $blogHtml = '';

            if (count($yazilar) > 0) {
                foreach ($yazilar as $y) {
                    $detayLink = 'blog-detay/' . $y['slug'];
                    $resim = $y['resim'] ?: 'assets/images/blog/blog1-1.png';
                    $tarih = date('d M. Y', strtotime($y['olusturma_tarihi']));

                    $blogHtml .= '
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-card">
                            <div class="blog-img">
                                <a href="' . $detayLink . '"><img src="' . $resim . '" alt="' . htmlspecialchars($y['baslik']) . '"></a>
                                <span class="blog-meta">' . $tarih . '</span>
                            </div>
                            <div class="blog-content">
                                <h4 class="title"><a href="' . $detayLink . '" title="">' . htmlspecialchars($y['baslik']) . '</a></h4>
                                <span>' . htmlspecialchars(mb_substr(strip_tags($y['ozet']), 0, 50)) . '...</span>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                $blogHtml = '<div class="col-12"><p class="text-center">Henüz blog yazısı eklenmemiş.</p></div>';
            }



            $pattern = '/(<section class="blog-sec.*?<div class="container">\s*<div class="row">)(.*?)(<\/div>\s*<nav)/s';

            $html = preg_replace_callback('/(<section class="blog-sec.*?<div class="container">\s*<div class="row">)(.*?)(<\/div>)/s', function ($m) use ($blogHtml) {

                return $m[1] . $blogHtml . $m[3];
            }, $html, 1);
        }

        if ($temaKodu === 'project') {
            $projeler = $this->db->query("SELECT * FROM projeler ORDER BY olusturma_tarihi DESC")->fetchAll(PDO::FETCH_ASSOC);
            $proHtml = '';

            if (count($projeler) > 0) {
                foreach ($projeler as $p) {
                    $detayLink = '/proje-detay/' . $p['slug'];
                    $resim = $p['resim'] ?: 'assets/images/project/project1-1.png';

                    $proHtml .= '
                    <div class="col-lg-4 col-md-6">
                        <div class="ser-card21">
                            <a href="#" title="" class="ibt-btn ibt-btn-outline-3 ibt-btn-rounded">
                                <span>' . htmlspecialchars($p['kategori'] ?: 'Project') . '</span>
                            </a>
                            <div class="empty2">
                                <div class="ser-content21">
                                    <h4 class="title"><a href="' . $detayLink . '" title="">' . htmlspecialchars($p['baslik']) . '</a></h4>
                                    <p>' . htmlspecialchars($p['ozet']) . '</p>
                                    <a class="ser-btn3" href="' . $detayLink . '" title="">Explore more</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                $proHtml = '<div class="col-12"><p class="text-center">Henüz proje eklenmemiş.</p></div>';
            }

            $html = preg_replace_callback('/(<section class="service-sec21.*?<div class="row">)(.*?)(<\/div>\s*<nav)/s', function ($m) use ($proHtml) {
                return $m[1] . $proHtml . $m[3];
            }, $html, 1);
        }

        if ($temaKodu === 'service') {
            $hizmetler = $this->db->query("SELECT * FROM hizmetler ORDER BY sira ASC")->fetchAll(PDO::FETCH_ASSOC);
            $serHtml = '';

            if (count($hizmetler) > 0) {
                foreach ($hizmetler as $h) {
                    $detayLink = $h['detay_link'] ?: '/hizmet-detay/' . $this->slugify($h['baslik']);
                    $ikon = $h['ikon'] ?: 'fontello icon-ai';

                    $serHtml .= '
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <div class="ser-card shadow-sm">
                            <div class="p-4">
                                <h4 class="title"><a href="' . $detayLink . '" title="">' . htmlspecialchars($h['baslik']) . '</a></h4>
                                <p>' . htmlspecialchars($h['ozet']) . '</p>
                            </div>
                            <a href="' . $detayLink . '" class="ser-btn">
                                <i class="icon fontello icon-button-arrow"></i>
                                <i class="icon2 fontello icon-button-arrow"></i>
                            </a>
                        </div>
                    </div>';
                }
            } else {
                $serHtml = '<div class="col-12"><p class="text-center">Henüz hizmet eklenmemiş.</p></div>';
            }

            $html = preg_replace_callback('/(<section class="service-sec6.*?<div class="row">)(.*?)(<\/div>)/s', function ($m) use ($serHtml) {
                return $m[1] . $serHtml . $m[3];
            }, $html, 1);
        }

        if ($isAdmin) {
            ob_start();
            include ROOT_DIR . '/gorunumler/tema/editor-bar.php';
            $toolbarHtml = ob_get_clean();
            $html = str_replace('</body>', $toolbarHtml . '</body>', $html);
        }

        if (!$isAdmin) {
            $gaCode = $this->db->query("SELECT deger FROM ayarlar WHERE anahtar = 'google_analytics'")->fetchColumn();
            if ($gaCode) {

                $html = str_replace('</head>', $gaCode . "\n</head>", $html);
            }
        }


        echo $html;
    }

    public function renderHome($temaKodu)
    {
        $this->renderThemeFile($temaKodu);
    }

    public function renderBlogDetail($post)
    {
        $dosya = ROOT_DIR . '/gorunumler/tema/blog-single.php';

        if (!file_exists($dosya)) {
            $dosya = ROOT_DIR . '/blog-single.htm';
            if (!file_exists($dosya)) {
                $this->render404();
                return;
            }
        }

        ob_start();
        include $dosya;
        $html = ob_get_clean();


        $html = preg_replace(
            '/(<section class="page-banner9">.*?<h1 class="title">)(.*?)(<\/h1>)/s',
            '$1' . htmlspecialchars($post['baslik']) . '$3',
            $html
        );


        $html = preg_replace(
            '/(<ul class="breadcrumbs">.*?<li>\/<\/li>\s*<li>)(.*?)(<\/li>)/s',
            '$1' . htmlspecialchars($post['baslik']) . '$3',
            $html
        );


        $resim = $post['resim'] ?: 'assets/images/blog/blog5-1.png';
        $html = preg_replace(
            '/(<div class="blog-img4">.*?<img src=")([^"]+?)(")/s',
            '$1' . $resim . '$3',
            $html
        );



        $html = preg_replace_callback(
            '/(<div class="blog-single-content">.*?<div class="blog-img4">.*?<\/div>)(.*?)(<h4 class="name">By)/s',
            function ($m) use ($post) {

                return $m[1] . "\n<div class='mt-4 dynamic-content'>" . $post['icerik'] . "</div>\n<div class='mb-5'></div>\n"; // m[3] match edilmisti ama onu replace grubuna dahil etmedik, yani silinmis olacakti eger komple match etseydik.
            },
            $html
        );

        $html = preg_replace_callback(
            '/(<div class="blog-img4">.*?<\/div>)(.*?)(<div class="post-meta2">)/s',
            function ($m) use ($post) {
                return $m[1] . "<div class='content-body mt-4'>" . $post['icerik'] . "</div>" . $m[3];
            },
            $html
        );


        $tarih = date('d M. Y', strtotime($post['olusturma_tarihi']));
        $auth = "Admin";

        $html = preg_replace(
            '/(<span class="blog-meta4">)(.*?)(<\/span>)/s',
            '$1' . $tarih . ' / ' . $auth . '$3',
            $html
        );

        $recentPosts = $this->db->query("SELECT * FROM blog_yazilari WHERE id != " . intval($post['id']) . " ORDER BY olusturma_tarihi DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
        $recentHtml = '<div class="post-widget side-widget2"><h4 class="side-bar-title">Recent posts</h4>';
        foreach ($recentPosts as $rp) {
            $rpLink = '/blog-detay/' . $rp['slug'];
            $rpResim = $rp['resim'] ?: 'assets/images/blog/post1-1.png';
            $rpTarih = date('d M. Y', strtotime($rp['olusturma_tarihi']));
            $recentHtml .= '
                <div class="recent-post">
                    <img src="' . $rpResim . '" alt="' . htmlspecialchars($rp['baslik']) . '">
                    <span class="sub-title">' . $rpTarih . '</span>
                    <h4 class="title"><a href="' . $rpLink . '" title="">' . htmlspecialchars($rp['baslik']) . '</a></h4>
                </div>';
        }
        $recentHtml .= '</div>';

        $html = preg_replace(
            '/<div class="post-widget side-widget2">.*?<\/div>\s*<\/div>/s',
            $recentHtml . "\n</div>",
            $html,
            1
        );

        echo $html;
    }

    public function renderProjectDetail($post)
    {
        $dosya = ROOT_DIR . '/gorunumler/tema/project-single.php';
        if (!file_exists($dosya)) {
            $this->render404();
            return;
        }

        ob_start();
        include $dosya;
        $html = ob_get_clean();

        $html = preg_replace(
            '/(<section class="page-banner6">.*?<h1 class="title">)(.*?)(<\/h1>)/s',
            '$1 / ' . htmlspecialchars($post['baslik']) . ' / $3',
            $html
        );

        $html = preg_replace(
            '/(<ul class="breadcrumbs">.*?<li class="items">\/<\/li>\s*<li>)(.*?)(<\/li>)/s',
            '$1' . htmlspecialchars($post['baslik']) . '$3',
            $html
        );

        $resim = $post['resim'] ?: 'assets/images/project/project7-1.png';
        $html = preg_replace(
            '/(<div class="project-img7">.*?<img src=")([^"]+?)(")/s',
            '$1' . $resim . '$3',
            $html
        );


        $html = preg_replace(
            '/(<h4 class="title">Client<\/h4>\s*<p>)(.*?)(<\/p>)/s',
            '$1' . htmlspecialchars($post['musteri'] ?: 'Company') . '$3',
            $html
        );
        $html = preg_replace(
            '/(<h4 class="title">Design<\/h4>\s*<p>)(.*?)(<\/p>)/s',
            '$1' . htmlspecialchars($post['kategori'] ?: 'Creative') . '$3',
            $html
        );


        $html = preg_replace_callback(
            '/(<h2 class="title animated-heading">Brief and project description<\/h2>)(.*?)(<div class="project-post-meta">)/s',
            function ($m) use ($post) {
                return $m[1] . "<div class='dynamic-project-content mt-4'>" . $post['icerik'] . "</div>" . $m[3];
            },
            $html
        );

        echo $html;
    }

    public function renderServiceDetail($hizmet)
    {
        $dosya = ROOT_DIR . '/gorunumler/tema/service-single.php';
        if (!file_exists($dosya)) {
            $this->render404();
            return;
        }

        ob_start();
        include $dosya;
        $html = ob_get_clean();

        $html = preg_replace(
            '/(<section class="page-banner10">.*?<h1 class="title">)(.*?)(<\/h1>)/s',
            '$1' . htmlspecialchars($hizmet['baslik']) . '$3',
            $html
        );

        $html = preg_replace(
            '/(<ul class="breadcrumbs">.*?<li>\/<\/li>\s*<li>)(.*?)(<\/li>)/s',
            '$1' . htmlspecialchars($hizmet['baslik']) . '$3',
            $html
        );


        $html = preg_replace_callback(
            '/(<div class="service-single-content">)(.*?)(<div class="service-process">)/s',
            function ($m) use ($hizmet) {
                return $m[1] . "<div class='dynamic-service-content mt-4 mb-5'>" . $hizmet['ozet'] . "</div>" . $m[3];
            },
            $html
        );

        echo $html;
    }

    private function slugify($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        if (empty($text))
            return 'n-a';
        return $text;
    }

    public function render404()
    {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Sayfa Bulunamadı</h1>";
        echo "<p>Aradığınız sayfa sistemde mevcut değil.</p>";
    }
}
