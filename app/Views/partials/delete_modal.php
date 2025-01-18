<?php if (!isset($is_forms_table)) { ?>
    <i class="fa-regular fa-trash-can interactive-delete smooth-transition"
       data-bs-toggle="modal" data-bs-target="#delete<?= $item['id'] ?>"
       title="<?= $item['name'] ?> löschen"></i>
<?php } ?>

<div class="modal fade" id="delete<?= $item['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"><?= $item['name'] ?> Löschen</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url(index_page()) . "/delete" . $href ?? "" ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    Wollen Sie <b><?= $item['name'] ?></b>
                    wirklich löschen?
                    <?= $additional_delete_text ?? "" ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Abbrechen
                    </button>
                    <button type="submit"
                            class="btn btn-danger">Löschen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
