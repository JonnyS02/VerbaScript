<div class="modal fade" id="groupModal" tabindex="-1" aria-labelledby="groupModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="groupModalLabel">Neue Gruppe hinzufügen</h1>
            </div>
            <form action="<?= base_url(index_page())."/editGroup"?>" method="post">
                <div class="modal-body pt-4">
                    <?php include __DIR__ . '/../form_parts/name.php'; ?>
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
    function editModal(name,id) {
        resetModal();
        $('#groupModalLabel').text('Gruppe ' + name + ' umbenennen');
        $('#name').val(name);
        $('#save').val('update');
        var hiddenInput = $('<input>').attr({
            type: 'hidden',
            id: 'id',
            name: 'id',
            value: id
        });
        $('#groupModal form').append(hiddenInput);
    }

    function resetModal() {
        $('#groupModalLabel').text('Neue Gruppe erstellen');
        $('#name').val('');
        $('#save').val('insert').prop("disabled", false);
        $('#originalName').remove();
        $("#name").removeClass("is-invalid");
        $("#validationName").empty();
        $('#id').remove();
    }
</script>