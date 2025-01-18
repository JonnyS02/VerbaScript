<form action="<?= base_url(index_page()) . "/updateOrder" ?>" method="post" id="form">
    <?php include __DIR__ . '/../partials/table.php'; ?>
    <?php include __DIR__ . '/../partials/save_buttons.php'; ?>
</form>
<script>
    function updateColumnStatus() {
        const rows = $('#table tr');
        let toggle = true;
        rows.each(function (index) {
            if (index === 0) return;
            const checkbox = $(this).find('td:nth-child(5) .form-check-input');
            const span = $(this).find('td:nth-child(5) span');
            if (checkbox.length && span.length) {
                if (checkbox.prop('checked')) {
                    span.text(toggle ? "Beginn" : "Ende");
                    toggle = !toggle;
                } else {
                    span.text("");
                }
            }
        });
    }

    $(document).ready(function () {
        updateColumnStatus();
        const rows = $('#table tr');
        rows.each(function (index) {
            if (index === 0) return;
            const checkbox = $(this).find('td:nth-child(5) .form-check-input');
            if (checkbox.length) {
                checkbox.on('change', updateColumnStatus);
            }
        });
    });
</script>

