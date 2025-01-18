<div class="d-flex gap-2 mb-3 px-1">
    <button type="button"
            id="toggle_button<?= $ai_section ?>" <?= $body == "forms/form_preview.php" ? 'disabled' : ''; ?>
            class="button-test flex-grow-1 px-3 py-2 text-primary-emphasis bg-primary-subtle border border-primary-subtle rounded-3 text-center toggle_button"
            onclick="toggleRecognition(<?= $ai_section ?>)">
        <span id="start_text<?= $ai_section ?>" class="me-1">Lokale Spracheingabe Starten</span>
        <i class="fa-solid fa-microphone" style="cursor:unset" id="microphone_icon<?= $ai_section ?>"></i>
    </button>
    <button type="button" id="aboard_button<?= $ai_section ?>" disabled
            class="button-test px-3 py-2 text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-3 text-center aboard_button"
            onclick="resetFields(<?= $ai_section ?>,false)">
        <span id="aboard_text<?= $ai_section ?>" class="me-1 d-none d-md-inline-block">Abbrechen</span> <i
                style="cursor:unset" class="fa-solid fa-circle-xmark "></i>
    </button>
</div>