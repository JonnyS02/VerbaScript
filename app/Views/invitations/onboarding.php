<form action="insertUserSubmit" method="post">
    <div class="mb-3">
        Um den Anmeldungsprozess abzuschließen, und Ihr Konto als „<?= $object['name'] ?>“ zu nutzen, wählen Sie bitte
        ein
        Passwort.
    </div>
    <?php
    include __DIR__ . '/../profile/set_password.php';
    include __DIR__ . '/../profile/save_profile_buttons.php';
    ?>
</form>