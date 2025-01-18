<form action="<?= $form_link ?>" method="post">
    <?php
    include __DIR__ . '/../partials/element_parts/form_parts/name.php';
    ?>
    <div class="form-group row mb-2">
        <label for="email" class="col-md-2 col-form-label">E-Mail:</label>
        <div class="col-md-10">
            <input type="email" class="form-control <?= isset($name_error) ? "is-invalid" : "" ?> " id="email"
                   autocomplete="off"
                   name="email" title="Die E-Mail kann nur der Nutzer selbst ändern." disabled
                   value="<?= $object['email'] ?? "" ?>">
        </div>
    </div>
    <?php include __DIR__ . '/../partials/role_select.php';
    include __DIR__ . '/../partials/element_parts/form_parts/buttons.php'; ?>
</form>