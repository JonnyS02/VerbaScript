<a class="btn btn-danger mx" title="Profil löschen" id="deleteModalTrigger" onclick="">
    Profil löschen
</a>
<div class="modal fade" id="exampleModal" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Profil Löschen</h1>
                <button type="submit" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Wollen Sie Ihr VerbaScript-Profil wirklich <span class="fw-bolder">löschen</span>?
                <p class="mt-3">All Ihre Nutzerdaten werden gelöscht.
                    <b>Dieser Prozess ist nicht wiederrufbar!</b></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Abbrechen
                </button>
                <button type="submit" class="btn btn-danger" name="task" value="delete_profile">Löschen</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#deleteModalTrigger').on('click', function (event) {
        const $passwordField = $('#password');
        const passwordValue = $passwordField.val();

        if (!passwordValue) {
            $passwordField[0].setCustomValidity('Bitte geben Sie Ihr aktuelles Passwort ein.');
            $passwordField[0].reportValidity();
            event.preventDefault();
        } else {
            $passwordField[0].setCustomValidity('');
            $('#exampleModal').modal('show');
        }
    });
</script>