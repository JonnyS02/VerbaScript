<div class="d-flex align-items-center w-100">
    <?php if (isset($is_order_table) && !empty($items) ) { ?>
        <a type="href" class="btn btn-primary me-2 position-relative" target="_blank"
           href="<?= base_url() . index_page() . "/formPreview" ?>"
           title="Zeigt das aktuelle Formular an. Änderungen in diesem Fenster werden direkt vom Vorschau-Fenster übernommen. ">Vorschau
            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger ms-2">
                LIVE
            </span>
        </a>
    <?php } ?>
    <div class="d-flex align-items-center w-100">
        <button disabled type="submit" onclick="" class="btn btn-primary" id="save<?= $item_name_sg ?>" title="Änderung übernehmen">
            <i class="fa-solid fa-floppy-disk"></i> <span class="d-none d-md-inline-block">Übernehmen</span>
        </button>
        <div class="form-check form-switch ms-2" title="Änderung automatisch speichern">
            <input checked class="form-check-input" type="checkbox" role="switch" id="autoSave<?= $item_name_sg ?>">
            <label class="form-check-label" for="autoSave<?= $item_name_sg ?>">Automatisch speichern</label>
        </div>
    </div>
    <button disabled id="reload<?= $item_name_sg ?>" type="button" onclick="location.reload()" class="btn btn-outline-info"
            data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Änderungen zurücksetzen"
            data-bs-original-title="Änderungen zurücksetzen">
        <i class="fa fa-refresh"></i>
    </button>
</div>
<script>
    localStorage.setItem('refreshNeeded', 'true');
    $('#autoSave<?= $item_name_sg  ?>').change(function () {
        localStorage.setItem('autoSave<?= $item_name_sg  ?>', $('#autoSave<?= $item_name_sg  ?>').is(':checked'));
        if ($('#autoSave<?= $item_name_sg  ?>').is(':checked')) {
            $('#save<?= $item_name_sg  ?>').prop('disabled', true);
            $('#reload<?= $item_name_sg  ?>').prop('disabled', true);
        } else {
            $('#save<?= $item_name_sg  ?>').prop('disabled', false);
            $('#reload<?= $item_name_sg  ?>').prop('disabled', false);
        }
    });
    if (localStorage.getItem('autoSave<?= $item_name_sg  ?>') === 'false') {
        $('#autoSave<?= $item_name_sg  ?>').prop('checked', false);
        $('#save<?= $item_name_sg  ?>').prop('disabled', false);
        $('#reload<?= $item_name_sg  ?>').prop('disabled', false);
    }

    function sendForm() {
        if ($('#autoSave<?= $item_name_sg ?>').is(':checked')) {
            $.ajax({
                type: 'POST',
                url: '<?= base_url(index_page()) . "/update" . $href ?>?ajax=true',
                data: $('#form').serialize(),
                success: function (response) {
                    console.log("AJAX request succeeded:", response);
                    localStorage.setItem('refreshNeeded', 'true');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("AJAX request failed:", textStatus, errorThrown);
                }
            });
        }
    }

    $('#table tbody').sortable({
        update: function (event, ui) {
            sendForm();
        }
    });
    $('input, select, textarea').change(function () {
        sendForm();
    });
</script>