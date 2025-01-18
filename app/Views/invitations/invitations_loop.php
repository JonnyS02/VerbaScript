<?php if (!empty($items)) {
    foreach ($items as $item) : ?>
        <tr class="no-border">
            <td class="text-truncate max-width-td">
                <span class="smooth-transition custom-link-style" onclick="$('#getForm<?= $item['id'] ?>').submit()"  title="<?= $item['name'] ?> anschauen"><?= $item['name'] ?></span>
                <form action="<?= base_url(index_page()) . "/get" . $href ?? "" ?>" method="post" id="getForm<?= $item['id'] ?>">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                </form>
            </td>
            <td class="text-truncate max-width-td">
                <span class="smooth-transition" title="<?= $item['email'] ?>"><?= $item['email'] ?></span>
            </td>
            <td>
                <span class="smooth-transition" title="<?= $item['role'] ?>"><?= $item['role'] ?></span>
            </td>
            <td>
                <?php include __DIR__ . '/../partials/delete_modal.php'; ?>
            </td>
        </tr>
    <?php endforeach;
} ?>