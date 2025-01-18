<?php if (!empty($items)) {
    foreach ($items as $item) : ?>
        <tr class="no-border">
            <td class="text-truncate max-width-td">
                <span class="smooth-transition custom-link-style" onclick="$('#fill<?= $item['id'] ?>').submit()"
                      title="<?= $item['name'] ?> ausfüllen"><?= $item['name'] ?> <?= $item['is_ready'] ? '<i class="fa-solid fa-check pe-none"></i>' : '' ?></span>
                <form id="fill<?= $item['id'] ?>" action="fill<?= $href ?>" method="post">
                    <input type="hidden" name="form_id" value="<?= $item['id'] ?>">
                </form>
            </td>
            <td class="text-truncate max-width-td">
                <span class="smooth-transition" title="<?= $item['template'] ?>"><?= $item['template'] ?></span>
            </td>
            <td>
                <span class="smooth-transition" title="<?= $item['last_edit'] ?>"><?= $item['last_edit'] ?></span>
            </td>
            <td>
                <div class="dropdown">
                    <i class="fa-solid fa-square-caret-down dropdown_toggle interactive smooth-transition"></i>
                    <ul class="dropdown-menu">
                        <?php if ($item['is_ready']) { ?>
                            <li>
                                <div class="interactive smooth-transition dropdown-item"
                                     onclick="$('#generateDocument<?= $item['id'] ?>docx').submit()"
                                     title="Word Datei von <?= $item['name'] ?> generieren">
                                    <form id="generateDocument<?= $item['id'] ?>docx"
                                          action="generateDocument" method="post">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="task" value=".docx">
                                        <i class="fa-solid fa-file-word"></i>
                                        Generieren
                                    </form>
                                </div>
                            </li>
                            <li>
                                <div class="interactive smooth-transition dropdown-item"
                                     onclick="$('#generateDocument<?= $item['id'] ?>pdf').submit()"
                                     title="PDF Datei von <?= $item['name'] ?> generieren">
                                    <form id="generateDocument<?= $item['id'] ?>pdf"
                                          action="generateDocument" method="post">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="task" value=".pdf">
                                        <i class="fa-solid fa-file-pdf"></i>
                                        Generieren
                                    </form>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        <?php } ?>
                        <li>
                            <div class="interactive smooth-transition dropdown-item"
                                 data-bs-toggle="modal" data-bs-target="#formModal"
                                 onclick="editModal('<?= $item['name'] ?>','<?= $item['id'] ?>')"
                                 title="<?= $item['name'] ?> umbenennen">
                                <i title="<?= $item['name'] ?> umbenennen"
                                   class="fa-regular fa-pen-to-square">
                                </i>
                                Umbenennen
                            </div>
                        </li>
                        <li>
                            <div class="interactive-delete smooth-transition dropdown-item" data-bs-toggle="modal"
                                 data-bs-target="#delete<?= $item['id'] ?>" title="<?= $item['name'] ?> löschen">
                                <i class="fa-regular fa-trash-can"></i>
                                Löschen
                            </div>
                        </li>
                    </ul>
                </div>
                <?php include __DIR__ . '/../partials/delete_modal.php'; ?>
            </td>
        </tr>
    <?php endforeach;
}
include 'form_modal.php';
?>

<script>
    $(document).ready(function () {
        $('.dropdown_toggle').on('click', function (e) {
            var $dropdownMenu = $(this).next('.dropdown-menu');
            if ($dropdownMenu.length === 0) return;
            var $originalParent = $dropdownMenu.parent();
            var isVisible = $dropdownMenu.hasClass('show');
            $('.dropdown-menu').removeClass('show');
            if (!isVisible) {
                if ($dropdownMenu.parent().is('body') === false) {
                    $('body').append($dropdownMenu);
                }
                var mouseX = e.clientX;
                var mouseY = e.clientY;
                $dropdownMenu.addClass('show')
                    .css({
                        position: 'absolute',
                        left: mouseX + 'px',
                        top: mouseY + 'px',
                        zIndex: 1000
                    });
                var closeDropdown = function (event) {
                    if (!$(event.target).closest($dropdownMenu).length && !$(event.target).is('.dropdown_toggle')) {
                        $dropdownMenu.removeClass('show');
                        $originalParent.append($dropdownMenu);
                        $(document).off('click', closeDropdown);
                    }
                };
                $(document).on('click', closeDropdown);
            }
        });
    });
</script>




