<form action="<?= $form_link ?>" method="post">
    <?php
    include __DIR__ . '/../partials/element_parts/form_parts/name.php';
    include __DIR__ . '/../partials/element_parts/form_parts/group.php';
    include __DIR__ . '/../partials/element_parts/form_parts/info.php';
    include __DIR__ . '/../partials/element_parts/form_parts/ai_info.php';
    ?>
    <div id="percentagesContainer">
        <?php if (isset($object['percentages']) && is_array($object['percentages'])) {
            for ($i = 0; $i < count($object['percentages']); $i++) { ?>
                <div class="form-group row mb-2">
                    <label class="col-md-2 col-form-label" title="Erreichbar unter ${NAME-p<?= $i + 1 ?>}"
                           for="percentage<?= $i + 1 ?>">Pz.-satz <?= $i + 1 ?>:</label>
                    <div class="col-md-10">
                        <input type="number" step="0.01" title="Erreichbar unter ${NAME-p<?= $i + 1 ?>}"
                               class="form-control" id="percentage<?= $i + 1 ?>"
                               name="percentage<?= $i + 1 ?>" placeholder="1,19"
                               value="<?= $object['percentages'][$i]['value'] ?? "" ?>">
                    </div>
                </div>
            <?php }
        } ?>
    </div>
    <div class="form-group row mb-4 mt-2">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <i class="fa-solid fa-square-plus smooth-transition" title="Prozentsatz hinzufügen"
               onclick="addPercentage()"></i>
            <i class="fa-solid fa-square-minus smooth-transition" title="Letzten Prozentsatz entfernen"
               onclick="removePercentage()"></i> &nbspProzentsatz berechnen
        </div>
    </div>
    <?php
    include __DIR__ . '/../partials/element_parts/form_parts/omitted_required.php';
    include __DIR__ . '/../partials/element_parts/form_parts/buttons.php';
    ?>
</form>

<script>
    function addPercentage() {
        const $container = $('#percentagesContainer');
        const newIndex = $container.children().length + 1;
        const $newDiv = $('<div>').addClass('form-group row mb-2').css('opacity', '0');
        const $newLabel = $('<label>').addClass('col-md-2 col-form-label').text('Pz.-satz ' + newIndex + ':').attr({
            for: 'percentage' + newIndex,
            title: 'Erreichbar unter ${NAME-p' + newIndex + '}'
        });
        const $newInputDiv = $('<div>').addClass('col-md-10');
        const $newInput = $('<input>').addClass('form-control').attr({
            type: 'number',
            name: 'percentage' + newIndex,
            id: 'percentage' + newIndex,
            placeholder: '1,19',
            step: '0.01',
            title: 'Erreichbar unter ${NAME-p' + newIndex + '}'
        });
        $newDiv.append($newLabel);
        $newInputDiv.append($newInput);
        $newDiv.append($newInputDiv);
        $container.append($newDiv);
        setTimeout(function () {
            $newDiv.css({
                transition: 'opacity 0.25s ease-in',
                opacity: '1'
            });
        }, 5);
    }

    function removePercentage() {
        const $container = $('#percentagesContainer');
        const $lastPercentage = $container.children().last();
        if ($container.children().length > 0) {
            $lastPercentage.css({
                transition: 'opacity 0.25s ease-out',
                opacity: '0'
            });
            setTimeout(function () {
                $lastPercentage.remove();
            }, 250);
        }
    }
</script>