<form action="<?= $form_link?>" method="post" enctype="multipart/form-data">
    <?php include __DIR__ . '/../partials/element_parts/form_parts/name.php'; ?>
    <div class="form-group row mb-2">
        <label for="file" class="col-md-2 col-form-label">Datei:</label>
        <div class="col-md-10">
            <input type="file" class="form-control <?= isset($file_error) ? "is-invalid" : "" ?>" id="file" name="file"
                   accept=".docx" <?=isset($object['filename'])?"":"required" ?>>
            <div id="validationFile" class="invalid-feedback mx-2">
                <?= $file_error ?? '' ?>
            </div>
            <?php if (isset($object['filename'])) : ?>
                <div class="mx-2 mt-1" style="color: var(--highlightColor)">
                    Wenn Sie eine neue Datei hochladen, wird die alte Datei „<?=$object['filename']?>“ überschrieben.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group row mb-1" title="Lässt Sprache zu Text umwandeln, um damit das Formular KI-gestützt automatisch auszufüllen.">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <div class="form-check form-switch">
                <label class="form-check-label" for="global_ai">Globale Spracheingabe möglich</label>
                <input class="form-check-input" type="checkbox" id="global_ai" name="global_ai" <?= isset($object['global_ai']) && $object['global_ai'] == 1 ? "checked" :"" ?>>
            </div>
        </div>
    </div>
    <div class="form-group row mb-3" title="Wenn die Vorlage aktiv ist, wird sie Nutzern angezeigt, um damit arbeiten zu können.">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <div class="form-check form-switch">
                <label class="form-check-label" for="display">Vorlage ist aktiv</label>
                <input class="form-check-input" type="checkbox" id="display" name="display" <?= isset($object['display']) && $object['display'] == 1 ? "checked" :"" ?>>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../partials/element_parts/form_parts/buttons.php'; ?>
</form>
<script>
    $("#file").change(function () {
        $("#file").removeClass("is-invalid");
        $("#validationFile").text("");
    });
</script>