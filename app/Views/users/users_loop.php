<?php if (!empty($items)) {
    foreach ($items as $item) : ?>
        <tr class="no-border">
            <td class="text-truncate max-width-td">
                <span class="smooth-transition custom-link-style" onclick="$('#user<?= $item['id'] ?>').submit()" title="<?= $item['name'] ?> verwalten"><?= $item['name'] ?> <?= isset($item['self']) ?'(Sie)' :''?></span>
                <form action="editUser" method="post" id="user<?= $item['id'] ?>">
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
                <?php include 'delete_user_modal.php' ?>
            </td>
        </tr>
    <?php endforeach;
} ?>