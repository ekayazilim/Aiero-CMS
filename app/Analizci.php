<?php

class Analizci
{
    private $db;
    private $rootDir;

    public function __construct($db, $rootDir)
    {
        $this->db = $db;
        $this->rootDir = $rootDir;
    }

    public function calistir()
    {
        $htmDosyalari = glob($this->rootDir . '/*.htm');
        $sonuc = ['sayfalar' => 0, 'temalar' => 0, 'hatalar' => []];

        $anadizinIcerik = file_get_contents($this->rootDir . '/index.htm');
        $parcalar = $this->temelParcalariAyir($anadizinIcerik);

        if (!is_dir($this->rootDir . '/gorunumler/tema/parcalar')) {
            mkdir($this->rootDir . '/gorunumler/tema/parcalar', 0777, true);
        }
        file_put_contents($this->rootDir . '/gorunumler/tema/parcalar/header.php', $parcalar['header']);
        file_put_contents($this->rootDir . '/gorunumler/tema/parcalar/footer.php', $parcalar['footer']);

        foreach ($htmDosyalari as $dosyaYolu) {
            $dosyaAdi = basename($dosyaYolu);
            $kod = str_replace('.htm', '', $dosyaAdi);

            $icerik = file_get_contents($dosyaYolu);
            $icerik = $this->linkleriDuzelt($icerik);


            $islenmisIcerik = $this->temayiIsle($icerik, $parcalar['header'], $parcalar['footer']);
            file_put_contents($this->rootDir . "/gorunumler/tema/{$kod}.php", $islenmisIcerik);

            $tur = (strpos($kod, 'index') === 0) ? 'anasayfa' : 'sayfa';


            $stmt = $this->db->prepare("INSERT IGNORE INTO temalar (tema_kodu, ad, onizleme_resmi) VALUES (?, ?, ?)");
            $ad = ucfirst(str_replace('-', ' ', $kod));
            $resim = 'assets/images/preview.jpg';
            $stmt->execute([$kod, $ad, $resim]);
            $sonuc['temalar']++;

            if ($tur !== 'anasayfa') {
                $stmtSayfa = $this->db->prepare("INSERT IGNORE INTO sayfalar (sayfa_kodu, slug, baslik) VALUES (?, ?, ?)");
                $slug = $kod;
                $baslik = ucfirst(str_replace('-', ' ', $kod));
                $stmtSayfa->execute([$kod, $slug, $baslik]);
                $sonuc['sayfalar']++;

            }
        }

        return $sonuc;
    }

    private function temelParcalariAyir($html)
    {


        $header = '';
        $footer = '';


        if (preg_match('/<header.*?<\/header>/s', $html, $matches)) {
            $header = $matches[0];
        } else {

            if (preg_match('/<nav.*?<\/nav>/s', $html, $matches)) {
                $header = $matches[0];
            }
        }

        if (preg_match('/<footer.*?<\/footer>/s', $html, $matches)) {
            $footer = $matches[0];
        }

        return ['header' => $header, 'footer' => $footer];
    }

    private function govdeyiAyikla($html)
    {
        $temiz = preg_replace('/<header.*?<\/header>/s', '', $html);
        $temiz = preg_replace('/<footer.*?<\/footer>/s', '', $temiz);

        $temiz = preg_replace('/^.*?<body.*?>/s', '', $temiz);
        $temiz = preg_replace('/<\/body>.*?$/s', '', $temiz);

        return trim($temiz);
    }

    private function temayiIsle($html, $headerRaw, $footerRaw)
    {

        $islenmis = $html;

        if (!empty($headerRaw)) {

            $islenmis = preg_replace('/<header.*?<\/header>/s', "<?php include __DIR__ . '/parcalar/header.php'; ?>", $islenmis);
        } else {
            $islenmis = preg_replace('/(<body.*?>)/i', "$1\n<?php include __DIR__ . '/parcalar/header.php'; ?>", $islenmis);
        }

        if (!empty($footerRaw)) {
            $islenmis = preg_replace('/<footer.*?<\/footer>/s', "<?php include __DIR__ . '/parcalar/footer.php'; ?>", $islenmis);
        } else {
            $islenmis = preg_replace('/(<\/body>)/i', "<?php include __DIR__ . '/parcalar/footer.php'; ?>\n$1", $islenmis);
        }

        return $islenmis;
    }

    private function linkleriDuzelt($html)
    {

        return preg_replace_callback('/href="([^"]+)\.htm"/', function ($matches) {
            $hedef = $matches[1];
            if (strpos($hedef, 'index') === 0) {

                if ($hedef == 'index')
                    return 'href="/"';
                return 'href="/?tema=' . $hedef . '"';
            }
            return 'href="/' . $hedef . '"';
        }, $html);
    }
}
