<form action="resetPasswordSubmit" method="post">
    <div class="mb-3">
        Um Ihr Passwort zurückzusetzen und Ihren Account zu reaktivieren, geben Sie bitte ein neues Passwort ein.
    </div>
    <?php
    include __DIR__ . '/../profile/set_password.php';
    include __DIR__ . '/../profile/save_profile_buttons.php';
    ?>
</form>