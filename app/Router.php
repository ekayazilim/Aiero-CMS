<?php

class Router
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function resolve($url)
    {
        $url = trim($url, '/');
        if ($url == '') {
            $this->renderHome();
            return;
        }

        if (strpos($url, 'yonetim') === 0) {
            $this->handleAdmin($url);
            return;
        }

        if ($url == 'iletisim-gonder') {
            $this->handleContactPost();
            return;
        }

        if (strpos($url, 'blog-detay/') === 0) {
            $parts = explode('/', $url);
            if (count($parts) >= 2) {
                $slug = $parts[1];
                $this->handleBlogDetail($slug);
                return;
            }
        }

        if (strpos($url, 'proje-detay/') === 0) {
            $parts = explode('/', $url);
            if (count($parts) >= 2) {
                $slug = $parts[1];
                $this->handleProjectDetail($slug);
                return;
            }
        }

        if (strpos($url, 'hizmet-detay/') === 0) {
            $parts = explode('/', $url);
            if (count($parts) >= 2) {
                $slug = $parts[1];
                $this->handleServiceDetail($slug);
                return;
            }
        }

        $this->handlePage($url);
    }

    private function renderHome()
    {
        $stmt = $this->db->prepare("SELECT deger FROM ayarlar WHERE anahtar = 'aktif_ana_sayfa_tema'");
        $stmt->execute();
        $temaKodu = $stmt->fetchColumn();

        if (!$temaKodu)
            $temaKodu = 'index';


        require_once ROOT_DIR . '/app/SayfaRender.php';
        $renderer = new SayfaRender($this->db);
        $renderer->renderHome($temaKodu);
    }

    private function handlePage($slug)
    {
        $stmt = $this->db->prepare("SELECT * FROM sayfalar WHERE slug = :slug AND durum = 1");
        $stmt->execute([':slug' => $slug]);
        $page = $stmt->fetch();

        if ($page) {
            require_once ROOT_DIR . '/app/SayfaRender.php';
            $renderer = new SayfaRender($this->db);
            $renderer->renderPage($page);
        } else {
            // 404
            http_response_code(404);
            require_once ROOT_DIR . '/app/SayfaRender.php';
            $renderer = new SayfaRender($this->db);
            $renderer->render404();
        }
    }

    private function handleAdmin($url)
    {
        $db = $this->db;


        $parts = explode('/', $url);
        $action = isset($parts[1]) ? $parts[1] : 'pano';

        if ($action == 'giris') {
            require_once ROOT_DIR . '/gorunumler/yonetim/giris.php';
            return;
        }

        if (!isset($_SESSION['admin_hazir']) || $_SESSION['admin_hazir'] !== true) {
            header('Location: /yonetim/giris');
            exit;
        }

        if ($action == 'cikis') {
            session_destroy();
            header('Location: /yonetim/giris');
            exit;
        }

        switch ($action) {
            case 'pano':
                require_once ROOT_DIR . '/gorunumler/yonetim/pano.php';
                break;
            case 'temalar':
                require_once ROOT_DIR . '/gorunumler/yonetim/temalar.php';
                break;
            case 'sayfalar':
                require_once ROOT_DIR . '/gorunumler/yonetim/sayfalar.php';
                break;
            case 'sayfa-duzenle':
                require_once ROOT_DIR . '/gorunumler/yonetim/sayfa-duzenle.php';
                break;
            case 'bolumler':
                require_once ROOT_DIR . '/gorunumler/yonetim/bolumler.php';
                break;
            case 'ayarlar':
                require_once ROOT_DIR . '/gorunumler/yonetim/ayarlar.php';
                break;
            case 'tema-detay':
                require_once ROOT_DIR . '/gorunumler/yonetim/tema-detay.php';
                break;
            case 'install-scan':
                require_once ROOT_DIR . '/install.php';
                break;
            case 'yoneticiler':
                require_once ROOT_DIR . '/gorunumler/yonetim/yoneticiler.php';
                break;
            case 'iletisim-mesajlari':
                require_once ROOT_DIR . '/gorunumler/yonetim/iletisim.php';
                break;
            case 'blog':
                require_once ROOT_DIR . '/gorunumler/yonetim/blog.php';
                break;
            case 'projeler':
                require_once ROOT_DIR . '/gorunumler/yonetim/projeler.php';
                break;
            case 'hizmetler':
                require_once ROOT_DIR . '/gorunumler/yonetim/hizmetler.php';
                break;
            case 'medya':
                require_once ROOT_DIR . '/gorunumler/yonetim/medya.php';
                break;
            default:
                echo "Yönetim sayfası bulunamadı.";
                break;
        }
    }

    private function handleBlogDetail($slug)
    {
        $stmt = $this->db->prepare("SELECT * FROM blog_yazilari WHERE slug = ?");
        $stmt->execute([$slug]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            require_once ROOT_DIR . '/app/SayfaRender.php';
            $renderer = new SayfaRender($this->db);
            $renderer->renderBlogDetail($post);
        } else {
            http_response_code(404);
            require_once ROOT_DIR . '/app/SayfaRender.php';
            $renderer = new SayfaRender($this->db);
            $renderer->render404();
        }
    }

    private function handleProjectDetail($slug)
    {
        $stmt = $this->db->prepare("SELECT * FROM projeler WHERE slug = ?");
        $stmt->execute([$slug]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($project) {
            require_once ROOT_DIR . '/app/SayfaRender.php';
            $renderer = new SayfaRender($this->db);
            $renderer->renderProjectDetail($project);
        } else {
            // 404
            http_response_code(404);
            require_once ROOT_DIR . '/app/SayfaRender.php';
            $renderer = new SayfaRender($this->db);
            $renderer->render404();
        }
    }

    private function handleServiceDetail($slug)
    {
        $hizmetler = $this->db->query("SELECT * FROM hizmetler")->fetchAll(PDO::FETCH_ASSOC);
        $found = null;

        foreach ($hizmetler as $h) {
            $s = preg_replace('~[^\pL\d]+~u', '-', $h['baslik']);
            $s = iconv('utf-8', 'us-ascii//TRANSLIT', $s);
            $s = preg_replace('~[^-\w]+~', '', $s);
            $s = trim($s, '-');
            $s = preg_replace('~-+~', '-', $s);
            $s = strtolower($s);

            if ($s === $slug) {
                $found = $h;
                break;
            }
        }

        if ($found) {
            require_once ROOT_DIR . '/app/SayfaRender.php';
            $renderer = new SayfaRender($this->db);
            $renderer->renderServiceDetail($found);
        } else {
            http_response_code(404);
            require_once ROOT_DIR . '/app/SayfaRender.php';
            $renderer = new SayfaRender($this->db);
            $renderer->render404();
        }
    }

    private function handleContactPost()
    {
        $db = $this->db;
        require_once ROOT_DIR . '/app/IletisimHandler.php';
    }
}
