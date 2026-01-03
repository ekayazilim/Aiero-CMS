document.addEventListener('DOMContentLoaded', () => {
    const editorBar = document.getElementById('aiero-editor-bar');
    if (!editorBar) return;

    let isDraft = false;
    let originalContent = {};
    const temaKodu = editorBar.dataset.tema;

    // 1. Editlenebilir Alanlari Hazirla
    const editables = document.querySelectorAll('[data-editable]');
    editables.forEach(el => {
        el.contentEditable = "true";
        el.classList.add('aiero-editable');

        // Orijinal veriyi sakla
        originalContent[el.dataset.key] = el.innerHTML;

        el.addEventListener('input', () => {
            isDraft = true;
            updateStatus('Düzenleniyor...', 'yellow');
        });

        // Resimler icin tiklama ve Surukle-Birak eventi
        if (el.tagName === 'IMG') {
            el.contentEditable = "false";
            el.style.cursor = "pointer";

            // Tiklama
            el.addEventListener('click', (e) => {
                e.preventDefault();
                openMediaManager(el);
            });

            // Surukle Birak
            el.addEventListener('dragover', (e) => {
                e.preventDefault();
                el.style.outline = "4px solid #4ade80"; // Yesil cerceve
                el.style.opacity = "0.7";
            });

            el.addEventListener('dragleave', (e) => {
                e.preventDefault();
                el.style.outline = "";
                el.style.opacity = "1";
            });

            el.addEventListener('drop', async (e) => {
                e.preventDefault();
                el.style.outline = "";
                el.style.opacity = "1";

                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    const file = e.dataTransfer.files[0];
                    if (!file.type.startsWith('image/')) {
                        alert('Lütfen sadece resim dosyası sürükleyin.');
                        return;
                    }

                    // Dosyayi yukle
                    await uploadImage(file, el);
                }
            });
        }
    });

    // Resim Yukleme Fonksiyonu
    async function uploadImage(file, imgEl) {
        const formData = new FormData();
        formData.append('dosya', file);

        updateStatus('Resim yükleniyor...', 'blue');

        try {
            const res = await fetch('/app/EditorApi.php?action=medya_yukle', {
                method: 'POST',
                body: formData
            });

            const json = await res.json();
            if (json.success) {
                imgEl.src = json.url; // Onizleme
                // Dataset guncellemeye gerek yok, save yaparken img.src okunuyor
                isDraft = true;
                updateStatus('Resim yüklendi', 'green');
            } else {
                alert('Hata: ' + (json.error || 'Bilinmeyen hata'));
                updateStatus('Hata oluştu', 'red');
            }
        } catch (err) {
            alert('Yükleme sırasında hata oluştu.');
        }
    }

    // 2. Toolbar Butonlari
    document.getElementById('btn-save-draft').addEventListener('click', () => saveContent('kaydet_taslak'));
    document.getElementById('btn-publish').addEventListener('click', () => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Yayınlamak İstediğinize Emin Misiniz?',
                text: "Tüm değişiklikler ziyaretçilere görünür olacak.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, Yayınla!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    saveContent('yayinla');
                }
            });
        } else {
            if (confirm('Tüm değişiklikler ziyaretçilere görünür olacak. Emin misiniz?')) {
                saveContent('yayinla');
            }
        }
    });
    document.getElementById('btn-revisions').addEventListener('click', openRevisions);

    // Fonksiyonlar
    async function saveContent(action) {
        const data = {};
        editables.forEach(el => {
            if (el.tagName === 'IMG') {
                data[el.dataset.key] = el.getAttribute('src');
            } else {
                data[el.dataset.key] = el.innerHTML;
            }
        });

        updateStatus('Kaydediliyor...', 'blue');

        try {
            const res = await fetch('/app/EditorApi.php?action=' + action, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ tema_kodu: temaKodu, icerik: data })
            });
            const json = await res.json();

            if (json.success) {
                updateStatus(json.msg, 'green');
                isDraft = false;
                if (action === 'yayinla') location.reload();
            } else {
                updateStatus('Hata: ' + json.error, 'red');
            }
        } catch (e) {
            updateStatus('Bağlantı Hatası', 'red');
        }
    }

    function updateStatus(msg, color) {
        const statusEl = document.getElementById('editor-status');
        statusEl.innerText = msg;
        statusEl.className = `text-xs font-bold text-${color}-500`;
    }

    // Medya Yoneticisi (Basit Input)
    let currentImgEl = null;
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.style.display = 'none';
    document.body.appendChild(fileInput);

    fileInput.addEventListener('change', async () => {
        if (!fileInput.files[0]) return;
        if (currentImgEl) {
            await uploadImage(fileInput.files[0], currentImgEl);
        }
    });

    function openMediaManager(imgEl) {
        currentImgEl = imgEl;
        fileInput.click();
    }

    // Revizyonlar
    async function openRevisions() {
        const res = await fetch('/app/EditorApi.php?action=revizyonlar&tema_kodu=' + temaKodu);
        const list = await res.json();

        let html = '<ul class="text-sm">';
        list.forEach(r => {
            html += `<li class="border-b py-2 flex justify-between">
                <span>${r.tarih} <span class="text-xs bg-gray-200 px-1 rounded">${r.tur}</span></span>
                <button onclick="restoreRevision(${r.id})" class="text-blue-600 hover:underline">Geri Yükle</button>
            </li>`;
        });
        html += '</ul>';

        // Basit Modal (SweetAlert varsa onu kullanabiliriz, yoksa custom)
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Geçmiş Versiyonlar',
                html: html,
                showConfirmButton: false
            });
        } else {
            alert('Revizyonlar:\n' + list.map(r => r.tarih).join('\n'));
        }
    }

    window.restoreRevision = async function (id) {
        if (!confirm('Bu versiyona dönmek istediğinize emin misiniz? Mevcut taslak içeriği değişecek.')) return;

        const res = await fetch('/app/EditorApi.php?action=revizyon_getir&id=' + id);
        const data = await res.json();

        // Editoru guncelle
        for (const [key, val] of Object.entries(data)) {
            const el = document.querySelector(`[data-key="${key}"]`);
            if (el) {
                if (el.tagName === 'IMG') el.src = val;
                else el.innerHTML = val;
            }
        }
        Swal.close();
        isDraft = true;
        updateStatus('Eski versiyon yüklendi. Kaydetmeyi unutmayın.', 'orange');
    }
});
