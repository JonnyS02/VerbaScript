<form action="editProfile" method="post" id="editForm">
    <div class="row mb-3 border-bottom pb-3">
        <?php
        $fields = [
            'Mandant' => $client,
            'Rolle' => $role,
            'Name' => $name,
            'E-Mail' => $email,
        ];

        foreach ($fields as $label => $value) {
            echo '<p class="col-3">' . $label . ':</p>';
            echo '<div class="col-9">';
            echo '<span class=" fw-bolder"> ' . $value . '</span>';
            echo '</div>';
        }
        ?>
    </div>
    <div class="form-group row mb-2">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <label class="form-check-label col-md-3 me-1" for="change_password">Passwort ändern:</label>
            <input class="form-check-input" type="checkbox" id="change_password"
                   name="change_password" <?= isset($change_password) ? "checked" : "" ?>>
        </div>
    </div>
    <?php include 'set_password.php'; ?>
    <div class="form-group row mb-2 mt-4">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <label class="form-check-label col-md-3 me-1" for="change_email">E-Mail ändern:</label>
            <input class="form-check-input" type="checkbox" id="change_email"
                   name="change_email" <?= isset($change_email) ? "checked" : "" ?>>
        </div>
    </div>
    <div class="form-group row mb-2 border-bottom mb-3 pb-3">
        <label for="email" class="col-md-3 col-form-label text-truncate">E-Mail:</label>
        <div class="col-md-9">
            <input type="email" required class="form-control <?= isset($email_status) ? "is-invalid" : "" ?>" id="email"
                   name="email" <?= isset($change_email) ? "" : "disabled" ?>
                   placeholder="<?= $email ?>" autocomplete="off" value="<?= $new_email ?? "" ?>">
            <div class="invalid-feedback mx-2">
                <?= $email_status ?? "" ?>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="password" class="col-md-3 col-form-label text-truncate">Verifizierung:</label>
        <div class="col-md-9">
            <input type="password" required class="form-control <?= isset($password_error) ? "is-invalid" : "" ?>"
                   id="password"
                   value="" name="password"
                   title="Änderungen mit aktuellem Passwort verifizieren"
                   placeholder="Passwort" autocomplete="off">
            <div class="invalid-feedback mx-2">
                <?= $password_error ?? "" ?>
            </div>
        </div>
    </div>
    <?php include 'save_profile_buttons.php'; ?>
</form>

<script>
    $('#change_password').change(function () {
        if ($(this).is(':checked')) {
            $('#new_password').prop('disabled', false);
            $('#repeat_password').prop('disabled', false);
            $('#edit_profile').prop('disabled', false);
        } else {
            $('#new_password').prop('disabled', true);
            $('#repeat_password').prop('disabled', true);
            $('#new_password').removeClass('is-invalid');
            $('#repeat_password').removeClass('is-invalid');
            $('#new_password').val('');
            $('#repeat_password').val('');
            if (!$('#change_email').is(':checked')) {
                $('#edit_profile').prop('disabled', true);
            }
        }
    });

    $('#change_email').change(function () {
        if ($(this).is(':checked')) {
            $('#email').prop('disabled', false);
            $('#edit_profile').prop('disabled', false);
        } else {
            $('#email').prop('disabled', true);
            $('#email').removeClass('is-invalid');
            $('#email').val('');
            if (!$('#change_password').is(':checked')) {
                $('#edit_profile').prop('disabled', true);
            }
        }
    });
    $('#edit_profile').on('click', function (event) {
        const $passwordField = $('#password');
        const passwordValue = $passwordField.val();

        if (!passwordValue) {
            $passwordField[0].setCustomValidity('Bitte geben Sie Ihr aktuelles Passwort ein.');
            $passwordField[0].reportValidity();
            event.preventDefault();
        } else {
            $passwordField[0].setCustomValidity('');
        }
    });
</script>