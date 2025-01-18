<div class="form-group row mb-2">
    <label for="new_password" class="col-md-3 col-form-label text-truncate">Neues Passwort:</label>
    <div class="col-md-9">
        <input type="password" required class="form-control <?= isset($new_password_status) ? "is-invalid" : "" ?>"
               id="new_password" value="" name="new_password" <?= isset($change_password) ? "" : "disabled" ?>
               placeholder="Neues Passwort" autocomplete="off">
        <div class="invalid-feedback mx-2">
            <?= $new_password_status ?? "" ?>
        </div>
    </div>
</div>
<div class="form-group row mb-2 ">
    <label for="repeat_password" class="col-md-3 col-form-label text-truncate">Bestätigung:</label>
    <div class="col-md-9">
        <input type="password" required class="form-control <?= isset($repeat_password_status) ? "is-invalid" : "" ?>"
               id="repeat_password" value="" name="repeat_password" <?= isset($change_password) ? "" : "disabled" ?>
               placeholder="Neues Passwort wiederholen" autocomplete="off">
        <div class="invalid-feedback mx-2">
            <?= $repeat_password_status ?? "" ?>
        </div>
    </div>
</div>
<script>
    $('#new_password, #repeat_password').on('input', function () {
        validatePasswords();
    });

    function validatePasswords() {
        const new_password = $('#new_password').val();
        const repeat_password = $('#repeat_password').val();

        if (repeat_password.length === 0) {
            $('#repeat_password')[0].setCustomValidity('');
            return;
        }
        if (new_password.length === 0) {
            $('#new_password')[0].setCustomValidity('');
            return;
        }
        if (new_password !== repeat_password) {
            $('#repeat_password')[0].setCustomValidity('Die Passwörter stimmen nicht überein.');
        } else {
            $('#repeat_password')[0].setCustomValidity('');
        }
    }
</script>