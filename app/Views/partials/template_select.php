<div class="form-group row mb-4 mx-1">
    <label for="template" class="col-md-3 col-form-label"
           title="Wählen Sie hier die Vorlage aus, von dem sie die Elemente und Anordnung bearbeiten möchten.">
        <b>Vorlage in Bearbeitung</b>:
    </label>
    <div class="col-md-9 m-auto">
        <div class="input-group">
            <select class="form-select text-truncate" id="template" name="template" autofocus
                    title="Wählen Sie hier die Vorlage aus, von dem sie die Elemente und Anordnung bearbeiten möchten.">
                <?php if (!empty($select_templates)) {
                    foreach ($select_templates as $select_template) : ?>
                        <option
                            <?= $select_template['name'] == $template_name ? "selected" : "" ?>
                                value="<?= $select_template['id'] ?>"><?= $select_template['name'] ?></option>
                    <?php endforeach;
                } else { ?>
                    <option value="">Keine Vorlage ausgewählt</option>
                <?php } ?>
            </select>
            <button type="button" class="btn btn-primary border-radius-right " title="Vorlage auswählen" onclick="triggerAjax()">
                Vorlage auswählen
            </button>
        </div>
    </div>
</div>
<hr>
<script>
    function triggerAjax() {
        $.ajax({
            url: '<?= base_url(index_page()) . "/setActiveTemplate" ?>',
            type: 'POST',
            data: {
                template: $('#template').val()
            },
            success: function () {
                location.reload();
            }
        });
    }
</script>
