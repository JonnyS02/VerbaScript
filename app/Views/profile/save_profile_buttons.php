<div class="form-group row mt-3">
    <div class="col-md-3"></div>
    <div class="col-md-9">
        <button type="submit" name="task" value="edit_profile" class="btn btn-primary"
                title="Profiländerung übernehmen" <?= isset($change_password) || isset($change_email) ? "" : "disabled" ?>
                id="edit_profile">
            Übernehmen
        </button>
        <?php if (!isset($remove_delete_profile_button)) {
            include __DIR__ . '/../partials/delete_profile_modal.php';
        } ?>
        <button type="button" onclick="location.reload()" class="btn btn-outline-info float-end"
                data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-original-title="Änderungen zurücksetzen">
            <i class="fa fa-refresh"></i>
        </button>
        <?php if (isset($success_password)) { ?>
            <div class="text-success-emphasis mx-2 mt-1"><?= $success_password ?></div>
        <?php } ?>
        <?php if (isset($success_email)) { ?>
            <div class="text-success-emphasis mx-2 mt-1"><?= $success_email ?></div>
        <?php } ?>
    </div>
</div>