<form id="form" method="post" class="form">
    <?php include __DIR__.'/elements/elements_loop.php'; ?>
    <div class="mt-2">
        <button type="submit"  class="btn btn-primary withValidation" name="task" value=".docx" title="Word Datei von TestFormular generieren"><i class="fa-solid fa-file-word"></i> <span class="d-none d-md-inline-block">Generieren</span></button>
        <button type="submit"  class="btn btn-danger mx withValidation" name="task" value=".pdf" title="PDF Datei von TestFormular generieren"><i class="fa-solid fa-file-pdf"></i> <span class="d-none d-md-inline-block">Generieren</span></button>
        <a href="<?= base_url(index_page())."/forms" ?>" title="Zurück zur Formulartabelle" class="btn btn-secondary float-end">Zurück</a>
    </div>
    <hr>
    <?php include __DIR__ . '/../partials/save_buttons.php'; ?>
</form>
<script>
    $(document).ready(function() {
        $('#save<?= $item_name_sg ?>').click(function() {
            $('#form').attr('novalidate', 'novalidate');
            $('#form').attr('action', '<?= base_url(index_page())."/updateForm"?>');
        });
        $('.withValidation').click(function() {
            $('#form').removeAttr('novalidate');
            $('#form').attr('action', '<?= base_url(index_page())."/generateDocument"?>');
        });
    });
</script>