<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formModalLabel">Formular umbenennen</h1>
            </div>
            <form action="<?= base_url(index_page()) . "/editFormSubmit" ?>" method="post">
                <div class="modal-body pt-4">
                    <?php include __DIR__ . '/../partials/element_parts/form_parts/name.php'; ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="task" value="insert" id="save">
                        Speichern
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function editModal(name, id) {
        resetModal();
        $('#formModalLabel').text('Formular ' + name + ' umbenennen');
        $('#name').val(name);
        $('#save').val('update');
        var hiddenInput = $('<input>').attr({
            type: 'hidden',
            id: 'id',
            name: 'id',
            value: id
        });
        $('#formModal form').append(hiddenInput);
    }

    function resetModal() {
        $("#name").removeClass("is-invalid");
        $("#validationName").empty();
    }
</script>