<?php if (isset($object['id'])) { ?>
    <input type="hidden" value="<?= $object['id'] ?>" name="id" id="id">
<?php } ?>
<div class="form-group row mt-2">
    <div class="col-md-2"></div>
    <div class="col-md-10">
        <button type="submit" class="btn btn-primary"
                title="<?= isset($object['name']) ? "Änderung übernehmen" : "Neuen Eintrag speichern" ?>"
                id="save"><?= isset($object['name']) ? "Übernehmen" : "Speichern" ?></button>
        <a href="<?= $aboard_link ?? "" ?>" type="submit" class="btn btn-secondary mx">Abbrechen</a>
        <button type="button" onclick="location.reload()" class="btn btn-outline-info float-end"
                data-bs-toggle="tooltip"
                data-bs-placement="right" title="Änderungen zurücksetzen">
            <i class="fa fa-refresh"></i>
        </button>
    </div>
</div>
