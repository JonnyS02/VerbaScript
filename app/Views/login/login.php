<form action="<?= base_url(index_page()) . "/validateLogin" ?>" method="post">
    <div class="form-group mb-3">
        <label for="email" class="col-form-label">E-Mail:</label>
        <input type="email" name="email" id="email" value="<?= $email ?? "" ?>"
               class="form-control <?= isset($email_error) ? "is-invalid" : "" ?>"
               placeholder="Benutzername" required autofocus>
        <div class="invalid-feedback mx-2">
            <?= $email_error ?? "" ?>
        </div>
    </div>
    <div class="form-group mb-3">
        <label for="password" class="col-form-label">Passwort:</label>
        <input type="password" name="password" id="password" value="<?= $password ?? "" ?>"
               class="form-control <?= isset($password_error) ? "is-invalid" : "" ?>" placeholder="Passwort"
               required>
        <div class="invalid-feedback mx-2">
            <?= $password_error ?? "" ?>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary w-100">Einloggen</button>
    </div>
</form>
