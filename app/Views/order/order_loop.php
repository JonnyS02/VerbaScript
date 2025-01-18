<?php if (!empty($items)) {
    $index = 1;
    foreach ($items as $item) :
        if ($item['element_type'] == 'group') {
            include 'order_loop_group.php';
        } else {
            ?>
            <tr class="no-border" >
                <td class="text-truncate grabbable text-truncate max-width-td" >
                    <input type="hidden" name="<?= $item['name'] ?>[id]" value="<?= $item['id'] ?>">
                    <span class="smooth-transition custom-link-style" title="<?= $item['name'] ?> (<?= $item['germanType'] ?>)" id="postLink<?= $item['name'] ?>">
                       <?= $item['name'] ?>
                    </span>
                    <script>
                        <?php $element = ucfirst($item['element_type']);?>
                        $(document).ready(function () {
                            $('#postLink<?= $item['name'] ?>').on('click', function (e) {
                                e.preventDefault();
                                var $form = $('<form>', {
                                    method: 'POST',
                                    action: '<?= base_url(index_page()) . "/edit" . $element ?>'
                                });
                                $('<input>').attr({
                                    type: 'hidden',
                                    name: 'id',
                                    value: '<?= $item['id'] ?>'
                                }).appendTo($form);
                                $('<input>').attr({
                                    type: 'hidden',
                                    name: 'to_order',
                                    value: 'true'
                                }).appendTo($form);
                                $form.appendTo('body').submit();
                            });
                        });
                    </script>
                </td>
                <td class="text-truncate max-width-td">
                    <span class="smooth-transition" title="<?= $item['group'] ?>"><?= $item['group'] ?></span>
                </td>
                <td>
                    <input class="form-control w-auto" type="number" name="<?= $item['name'] ?>[field_length]"
                           title="<?= $columns[2]['title'] ?>"
                           id="field_lengthNumber<?= $item['name'] ?>"
                           aria-label="field_lengthNumber<?= $item['name'] ?>"
                           value="<?= $item['field_length'] ?>"
                           min="1" step="1" max="12">
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               title="<?= $columns[3]['title'] ?>"
                               name="<?= $item['name'] ?>[separate]"
                               id="separateCheck<?= $item['name'] ?>"
                               aria-label="separateCheck<?= $item['name'] ?>"<?= $item['separate_after'] == 1 ? "checked" : "" ?>>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               title="<?= $columns[4]['title'] ?>"
                               name="<?= $item['name'] ?>[ai_marker]"
                               id="aiCheck<?= $item['name'] ?>"
                               aria-label="aiCheck<?= $item['name'] ?>"<?= $item['ai_marker'] != 0 ? "checked" : "" ?>>
                        <span id="aiCheck<?= $item['name'] ?>span">

                        </span>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               title="<?= $columns[5]['title'] ?>"
                               name="<?= $item['name'] ?>[display]"
                               id="displayCheck<?= $item['name'] ?>"
                               aria-label="displayCheck<?= $item['name'] ?>"<?= $item['display'] == 1 ? "checked" : "" ?>>
                    </div>
                </td>
            </tr>
        <?php }
        $index++;
    endforeach;
} ?>
