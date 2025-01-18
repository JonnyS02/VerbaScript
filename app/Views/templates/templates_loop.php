<?php if (!empty($items)) {
    foreach ($items as $item) : ?>
        <tr class="no-border">
            <td class="text-truncate max-width-td">
                <span class="smooth-transition custom-link-style" onclick="$('#form<?= $item['id'] ?>').submit()" title="<?= $item['name'] ?> bearbeiten"><?= $item['name'] ?></span>
                <?php if ($item['display'] == 0) { ?>
                    <i class="fa-regular fa-eye-slash"
                       title="<?= $item['name'] ?> ist nicht aktiv und wird Nutzern nicht angezeigt."></i>
                <?php } ?>
                <form id="form<?= $item['id'] ?>"
                      action="<?= base_url(index_page()) . "/edit" . $href ?? "" ?>" method="post">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                </form>
            </td>
            <td>
                <form action="<?= base_url(index_page()) . "/getTemplateFile" ?>" method="post"
                      id="getTemplateFile<?= $item['id'] ?>">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <i class="fa-solid fa-download interactive smooth-transition"
                       onclick="$('#getTemplateFile<?= $item['id'] ?>').submit()"
                       title="Muster-Datei von <?= $item['name'] ?> herunterladen"></i>
                </form>
            </td>
            <td>
                <?php include __DIR__ . '/../partials/delete_modal.php'; ?>
            </td>
        </tr>
    <?php endforeach;
} ?>
