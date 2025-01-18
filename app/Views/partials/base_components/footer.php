<footer class="d-flex justify-content-between pt-3 mt-5 border-top">
    <p class="text-body-secondary">© <?= date("Y") ?> Jonathan Stengl</p>
    <a class="text-body-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom"
       title="Quelle des Webseiten Icons: www.freepik.com"
       href="https://www.freepik.com">Icon by Freepik</a>
</footer>
<?php include __DIR__ . '/../manual/offcanvas.php' ?>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>