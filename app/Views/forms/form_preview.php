<?php include __DIR__ . '/elements/elements_loop.php'; ?>
<div class="mt-2">
    <button type="button" disabled class="btn btn-primary"><i class="fa-solid fa-file-word"></i> <span class="d-none d-md-inline-block">Generieren</span></button>
    <button type="button" disabled class="btn btn-danger mx"><i class="fa-solid fa-file-pdf"></i> <span class="d-none d-md-inline-block">Generieren</span></button>
</div>
<script>
    localStorage.setItem('refreshNeeded', 'false');
    $(window).on('storage', function (event) {
        if (event.originalEvent.newValue === 'true') {
            location.reload();
            localStorage.setItem('refreshNeeded', 'false');
        }
    });
</script>