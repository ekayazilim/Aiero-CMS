<?php

class TemaAnalizci
{
    private $db;
    private $temaDizini;

    public function __construct($db)
    {
        $this->db = $db;
        $this->temaDizini = ROOT_DIR . '/gorunumler/tema';
    }

    public function temayiAnalizEt($temaKodu)
    {
        $dosyaYolu = $this->temaDizini . "/{$temaKodu}.php";
        if (!file_exists($dosyaYolu)) {
            return false;
        }

        $html = file_get_contents($dosyaYolu);

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $degiskenSayaci = 1;
        $eklenenVeriler = [];

        $resimler = $xpath->query('//img');
        foreach ($resimler as $img) {
            $src = $img->getAttribute('src');
            if (strpos($src, '{{') !== false)
                continue;


            $anahtar = "img_" . $degiskenSayaci++;
            $etiket = "Resim " . $anahtar;

            $eklenenVeriler[] = [
                'anahtar' => $anahtar,
                'deger' => $src,
                'tur' => 'resim',
                'etiket' => $etiket
            ];

            $img->setAttribute('src', "{{" . $anahtar . "}}");
        }


        $metinTaglari = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'a', 'strong', 'li'];
        foreach ($metinTaglari as $tag) {
            $nodelar = $xpath->query("//{$tag}");
            foreach ($nodelar as $node) {
                if ($node->childNodes->length === 1 && $node->firstChild->nodeType === XML_TEXT_NODE) {
                    $text = trim($node->textContent);
                    if (strlen($text) < 3)
                        continue;
                    if (strpos($text, '{{') !== false)
                        continue;

                    $anahtar = "txt_" . $degiskenSayaci++;
                    $etiket = strtoupper($tag) . " Metni " . $anahtar;

                    $eklenenVeriler[] = [
                        'anahtar' => $anahtar,
                        'deger' => $text,
                        'tur' => 'yazi',
                        'etiket' => $etiket
                    ];

                    $node->nodeValue = "{{" . $anahtar . "}}";
                }
            }
        }



        unset($dom);

        $yeniHtml = $html;
        $yeniVeriler = [];
        $sayac = 1;

        $yeniHtml = preg_replace_callback('/<img[^>]+src="([^">]+)"[^>]*>/i', function ($matches) use (&$yeniVeriler, &$sayac) {
            $fullTag = $matches[0];
            $src = $matches[1];

            if (strpos($src, '<?') !== false || strpos($src, '{{') !== false)
                return $fullTag;

            $anahtar = "t_img_" . $sayac++;
            $yeniVeriler[] = [
                'anahtar' => $anahtar,
                'deger' => $src,
                'tur' => 'resim',
                'etiket' => "Görsel $sayac"
            ];

            return str_replace($src, "{{" . $anahtar . "}}", $fullTag);
        }, $yeniHtml);

        $yeniHtml = preg_replace_callback('/<(h[1-6])[^>]*>(.*?)<\/\1>/s', function ($matches) use (&$yeniVeriler, &$sayac) {
            $tag = $matches[1];
            $icMetin = $matches[2];

            if (trim($icMetin) == '')
                return $matches[0];

            $anahtar = "t_baslik_" . $sayac++;
            $yeniVeriler[] = [
                'anahtar' => $anahtar,
                'deger' => trim($icMetin),
                'tur' => 'yazi',
                'etiket' => strtoupper($tag) . " Başlık"
            ];

            return str_replace($icMetin, "{{" . $anahtar . "}}", $matches[0]);
        }, $yeniHtml);

        $yeniHtml = preg_replace_callback('/<p[^>]*>(.*?)<\/p>/s', function ($matches) use (&$yeniVeriler, &$sayac) {
            $icMetin = $matches[1];
            if (strlen(trim($icMetin)) > 1000)
                return $matches[0];

            $anahtar = "t_yazi_" . $sayac++;
            $yeniVeriler[] = [
                'anahtar' => $anahtar,
                'deger' => trim($icMetin),
                'tur' => 'yazi',
                'etiket' => "Paragraf Yazısı"
            ];
            return str_replace($icMetin, "{{" . $anahtar . "}}", $matches[0]);
        }, $yeniHtml);


        $stmt = $this->db->prepare("DELETE FROM tema_icerikleri WHERE tema_kodu = ?");
        $stmt->execute([$temaKodu]);

        $stmtEkle = $this->db->prepare("INSERT INTO tema_icerikleri (tema_kodu, anahtar, deger, tur, etiket) VALUES (?, ?, ?, ?, ?)");

        foreach ($yeniVeriler as $veri) {
            $stmtEkle->execute([$temaKodu, $veri['anahtar'], $veri['deger'], $veri['tur'], $veri['etiket']]);
        }

        file_put_contents($dosyaYolu, $yeniHtml);

        return count($yeniVeriler);
    }
}
