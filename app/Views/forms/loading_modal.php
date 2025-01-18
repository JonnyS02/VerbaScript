<div class="modal fade" id="loading_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="loading_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h1 class="modal-title fs-5 " id="loading_modal_label">Modal title</h1>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <div class="spinner-border text-info" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleLoadingModal(message) {
        $('#loading_modal_label').text(message);
        $('#loading_modal').modal('toggle');
    }
</script>