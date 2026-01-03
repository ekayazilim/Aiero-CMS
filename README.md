# Aiero CMS - Kurumsal İçerik Yönetim Sistemi

Aiero CMS; özellikle yapay zeka ajansları, teknoloji şirketleri ve dijital bürolar için tasarlanmış, modern, yüksek performanslı ve hafif bir İçerik Yönetim Sistemidir. Gelişmiş bir yönetim paneli, dinamik tema motoru ve kapsamlı içerik yönetim modülleri sunar.

**Geliştiren: Eka Yazılım ve Bilişim Sistemleri**

---

## 🚀 Öne Çıkan Özellikler

- **Dinamik Tema Motoru:** Veritabanı odaklı içerik enjeksiyonu ile Aiero temasının otomatik render edilmesi.
- **Görsel Düzenleme Deneyimi:** Tema içeriklerini görsel dostu kimliklendirme sistemi ile kolayca yönetme.
- **Rol Tabanlı Erişim Kontrolü (RBAC):**
  - **Admin:** Ayarlar, kullanıcı yönetimi ve sistem çekirdeğine tam erişim.
  - **Editör:** İçerik modüllerine (Blog, Projeler, Hizmetler, Medya) sınırlı erişim.
- **Çekirdek Modüller:**
  - **Blog Yönetimi:** Otomatik slug (URL) oluşturma ile makale oluşturma, düzenleme ve silme.
  - **Portfolyo / Proje Yönetimi:** Çalışmalarınızı özel kategoriler ve teknik detaylarla sergileme.
  - **Hizmet Yönetimi:** Profesyonel hizmetlerinizi ikonlar ve özel bağlantılarla listeleme.
- **Merkezi Medya Kütüphanesi:** Görsel varlıklarınızı (`JPG`, `PNG`, `SVG`, `WEBP`) güvenli bir şekilde yükleme ve yönetme.
- **İletişim Yönetimi:** Veritabanı kaydı ve yönetici tarafında görüntüleme özellikli entegre iletişim formu.
- **Gelişmiş Ayarlar:** Site başlığı, SEO etiketleri (Google Analytics), bakım modu ve aktif tema yönetimi.

---

## 🛠️ Kullanılan Teknolojiler

- **Backend:** PHP 7.4 (Basitlik ve hız için Prosedürel ve OOP karışımı)
- **Veritabanı:** MySQL / MariaDB (Güvenli işlemler için PDO)
- **Frontend:** TailwindCSS, Lucide Icons, SweetAlert2, Sora & Manrope Google Fontları
- **Sunucu:** Apache (Temiz SEF URL'ler için `.htaccess`)

---

## 💻 Kurulum

1. **Projeyi klonlayın:**
   ```bash
   git clone https://github.com/ekayazilim/aiero-cms.git
   ```
2. **Veritabanı Kurulumu:**
   - phpMyAdmin üzerinden yeni bir veritabanı oluşturun.
   - En güncel şema için `cms_db.sql` dosyasını içe aktarın.
3. **Yapılandırma:**
   - `config/database.php` dosyasını açın ve veritabanı bilgilerinizi girin.
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'veritabani_adiniz');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```
4. **Apache Yapılandırması:**
   - Sunucunuzda `mod_rewrite` modülünün etkin olduğundan emin olun. Dahili `.htaccess` dosyası yönlendirmeleri devralacaktır.
5. **Yönetim Paneline Giriş:**
   - URL: `http://siteniz.com/yonetim`
   - Varsayılan Kullanıcı: `admin@admin.com`
   - Varsayılan Şifre: `admin`
   - *Not: Giriş yaptıktan sonra şifrenizi hemen değiştirmeniz önerilir.*

---

## 📂 Proje Yapısı

- `/app`: Çekirdek mantık (Router, SayfaRender, Kütüphane).
- `/assets`: Frontend varlıkları (CSS, JS, Görseller).
- `/config`: Veritabanı ve ortam yapılandırmaları.
- `/gorunumler/yonetim`: Yönetim paneli görünümleri.
- `/gorunumler/tema`: Ön yüz tema dosyaları.

---

## 📄 Lisans ve Atıf

Bu proje **Eka Yazılım ve Bilişim Sistemleri** tarafından geliştirilmiş ve sürdürülmektedir. 

Hata bildirimleri veya özellik talepleri için issue açabilir veya pull request gönderebilirsiniz.

---

