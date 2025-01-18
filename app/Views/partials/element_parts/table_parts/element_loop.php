<?php
if (!empty($items)) {
    foreach ($items as $item) { ?>
        <tr class="no-border">
            <td class="text-truncate max-width-td">
                <?php if (!isset($group_table)) { ?>
                    <span class="smooth-transition custom-link-style" onclick="$('#form<?= $item['id'] ?>').submit()"
                          title="<?= $item['name'] ?> bearbeiten"><?= $item['name'] ?></span>
                    <form action="<?= "edit" . $href ?? "" ?>" method="post" id="form<?= $item['id'] ?>">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    </form>
                <?php } else { ?>
                    <span class="smooth-transition"
                          title="<?= $item['name'] ?>"><?= $item['name'] ?></span>
                <?php } ?>
            </td>
            <?php if (!isset($group_table)) { ?>
                <td class="text-truncate max-width-td">
                    <span class="smooth-transition "
                          title="<?= $item['group'] ?? "" ?>"><?= $item['group'] ?? "" ?></span>
                </td>
            <?php } ?>
            <?php if (isset($group_table)) { ?>
                <td>
                    <i title="<?= $item['name'] ?> umbenennen" data-bs-toggle="modal"
                       data-bs-target="#groupModal"
                       class="fa-regular fa-pen-to-square interactive smooth-transition"
                       onclick="editModal('<?= $item['name'] ?>','<?= $item['id'] ?>')"></i>
                </td>
            <?php } ?>
            <td>
                <?php include __DIR__ . '/../../delete_modal.php'; ?>
            </td>
        </tr>
    <?php }
}
if (isset($group_table)) {
    include __DIR__ . '/group_modal.php';
} ?>
