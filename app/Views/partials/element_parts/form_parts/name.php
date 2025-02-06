<div class="form-group row mb-2">
    <label for="name" class="col-md-2 col-form-label">Name:</label>
    <div class="col-md-10">
        <input type="text" class="form-control <?= isset($name_error) ? "is-invalid" : "" ?> " id="name"
               autocomplete="off"
               name="name" required placeholder="Einzigartiger Name" title="Einzigartiger Name"
               oninput="checkValidName()" value="<?= $object['name'] ?? "" ?>" autofocus>
        <div id="validationName" class="invalid-feedback mx-2">
            <?= $name_error ?? '' ?>
        </div>
    </div>
</div>

<script>
    <?= $js_arrays ?>

    function checkValidName() {
        const name = $("#name");
        const validationName = $("#validationName");
        const enteredName = name.val().toUpperCase();
        const existingIndex = $.inArray(enteredName, existingNames.map(name => name.toUpperCase()));
        if (existingIndex !== -1) {
            return showErrorInput(true, "Dieser Name ist bereits vergeben.", name, validationName);
        }
        const foundName = $.grep(specialNames, function (specialName) {
            return specialName.name.toUpperCase() === enteredName;
        });
        if (foundName.length > 0) {
            return showErrorInput(true, foundName[0].errorMessage, name, validationName);
        }
        return showErrorInput(false, "", name, validationName);
    }

    function showErrorInput(show, message, input, errorMessage) {
        const $saveButton = $("#save");
        if (show) {
            input.addClass("is-invalid");
            errorMessage.text(message);
            $saveButton.prop("disabled", true);
            return false;
        } else {
            input.removeClass("is-invalid");
            errorMessage.text("");
            $saveButton.prop("disabled", false);
            return true;
        }
    }

    <?php if(isset($check_name_for_valid_ending)) { ?>
    $(document).ready(function () {
        checkNameForValidEnding();
    });

    function checkNameForValidEnding() {
        const forbiddenEndings = ["-b", "-l", "-w"];
        const inputsAndTextareas = document.querySelectorAll('input[id="name"]');
        inputsAndTextareas.forEach(function (element) {
            element.addEventListener('input', function () {
                let errorMessage = '';
                const endsWithForbiddenEnding = forbiddenEndings.some(ending => element.value.endsWith(ending));
                const endsWithPNumber = /-p\d+$/.test(element.value);
                if (endsWithForbiddenEnding || endsWithPNumber) {
                    errorMessage = 'Der Name darf nicht mit "-b", "-l", "-w" oder "-p" gefolgt von einer Zahl enden.';
                }
                element.setCustomValidity(errorMessage);
            });
        });
    }
    <?php } ?>
</script>
