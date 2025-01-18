<form action="insertInvitationSubmit" method="post">
    <?php if (isset($object) && !isset($object['email-error'])) { ?>
        <div class="form-group row mb-2">
            <label for="name" class="col-md-2 col-form-label">Name:</label>
            <div class="col-md-10">
                <input type="text" class="form-control" id="name" autocomplete="off" name="name"
                       title="Name der eingeladenen Person"
                       value="<?= $object['name'] ?>"
                       disabled>
            </div>
        </div>
    <?php } else {
        include __DIR__ . '/../partials/element_parts/form_parts/name.php';
    } ?>
    <div class="form-group row mb-2">
        <label for="email" class="col-md-2 col-form-label">E-Mail:</label>
        <div class="col-md-10">
            <input type="email" class="form-control <?= isset($object['email-error']) ? 'is-invalid' : '' ?>" id="email"
                   autocomplete="off" name="email"
                   placeholder="Einzigartige E-Mail"
                   title="An diese Email <?= (isset($object) && !isset($object['email-error'])) ? 'wurde' : 'wird' ?> die Einladung geschickt."
                   value="<?= $object['email'] ?? "" ?>" <?= (isset($object) && !isset($object['email-error'])) ? 'disabled' : 'required' ?>>
            <div id="validation_email" class="invalid-feedback mx-2"><?= $object['email-error'] ?? '' ?></div>
        </div>
    </div>
    <?php include __DIR__ . '/../partials/role_select.php'; ?>
    <div class="form-group row mb-2">
        <label for="message" class="col-md-2 col-form-label">Nachricht:</label>
        <div class="col-md-10">
        <textarea aria-label="message" class="form-control" id="message" name="message"
                  placeholder="Nachricht der Einladung"
                  rows="3" <?= (isset($object) && !isset($object['email-error'])) ? 'disabled' : 'required' ?>
                  title="Nachricht der Einladung"><?= $object['message'] ?? "Guten Tag, wir freuen uns, Sie in unserem Team willkommen zu heißen. Bitte klicken Sie auf den Button unten, um Ihre Registrierung abzuschließen und sich bei VerbaScript anzumelden." ?>
        </textarea>
            <?php if (!(isset($object) && !isset($object['email-error']))) { ?>
                <div class="mx-2 mt-1" style="color: var(--highlightColor)">
                    Wenn Sie diesen Eintrag speichern, wird eine Einladung an die oben angegebene E-Mail gesendet.
                </div>
            <?php } ?>
        </div>
    </div>
    <?php if (isset($object) && !isset($object['email-error'])) { ?>
        <div class="mt-2 row">
            <div class="col-md-2 col-form-label"></div>
            <div class="col-md-10">
                <a href="invitations" title="Zurück zur Formulartabelle"
                   class="btn btn-secondary">Zurück</a>
            </div>
        </div>
    <?php } else {
        include __DIR__ . '/../partials/element_parts/form_parts/buttons.php';
    } ?>
</form>
