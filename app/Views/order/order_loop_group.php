<tr class="no-border">
    <td class="text-truncate grabbable">
        <input type="hidden" name="group-<?= $item['name'] ?>[id]" value="<?= $item['id'] ?>">
        <input type="hidden" name="group-<?= $item['name'] ?>[isGroup]" value="true">
    </td>
    <td class="text-truncate grabbable">
        <span onclick="window.location.href='<?= base_url(index_page()) . '/groups' ?>'"
              class="smooth-transition custom-link-style" title="<?= $item['name'] ?>">
            <b><?= $item['name'] ?></b>
        </span>
    </td>
    <td>
    </td>
    <td>
    </td>
    <td>
    </td>
    <td>
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                   title="Zeige die Gruppe im Formular an."
                   name="group-<?= $item['name'] ?>[display]"
                   id="displayCheck<?= $item['name'] ?>"
                   aria-label="displayCheck<?= $item['name'] ?>"<?= $item['display'] == 1 ? "checked" : "" ?>>
        </div>
    </td>
</tr>
