<?php
// Editor Toolbar
?>
<style>
    #aiero-editor-bar {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #1f2937;
        color: white;
        padding: 10px 20px;
        border-radius: 50px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 15px;
        font-family: sans-serif;
    }

    .aiero-btn {
        background: #374151;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }

    .aiero-btn:hover {
        background: #4b5563;
    }

    .aiero-btn-primary {
        background: #2563eb;
    }

    .aiero-btn-primary:hover {
        background: #1d4ed8;
    }

    .aiero-editable {
        outline: 2px dashed #3b82f6;
        cursor: text;
    }

    .aiero-editable:hover {
        outline-color: #2563eb;
        background: rgba(59, 130, 246, 0.1);
    }

    img.aiero-editable {
        cursor: pointer;
    }
</style>

<div id="aiero-editor-bar" data-tema="<?php echo $temaKodu; ?>">
    <div style="display:flex; flex-direction:column; margin-right: 10px;">
        <span style="font-weight:bold; font-size:12px;">Aiero Editör</span>
        <span id="editor-status" style="font-size:10px; color:#9ca3af;">Hazır</span>
    </div>

    <button class="aiero-btn" id="btn-revisions">↺ Geçmiş</button>
    <div style="width: 1px; height: 20px; background: #4b5563;"></div>
    <button class="aiero-btn" id="btn-save-draft">Taslak Kaydet</button>
    <button class="aiero-btn aiero-btn-primary" id="btn-publish">Yayınla</button>
</div>

<script src="/assets/js/editor.js"></script>
<!-- SweetAlert2 for Modals -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>