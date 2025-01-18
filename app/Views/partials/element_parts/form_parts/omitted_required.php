<div class="form-group row mb-1">
    <div class="col-md-2"></div>
    <div class="col-md-10">
        <div class="form-check form-switch">
            <label class="form-check-label" for="omit">„entfällt“ einfügen wenn leer</label>
            <input class="form-check-input" type="checkbox" id="omit"
                   name="omit" <?= (isset($object['omit']) && $object['omit']) ? "checked" : "" ?>
                   onchange="toggleSwitches(this.id)">
        </div>
    </div>
</div>
<div class="form-group row mb-3">
    <div class="col-md-2"></div>
    <div class="col-md-10">
        <div class="form-check form-switch">
            <label class="form-check-label" for="required">Ist verpflichtend auszufüllen</label>
            <input class="form-check-input" type="checkbox" id="required"
                   name="required" <?= (isset($object['required']) && $object['required']) ? "checked" : "" ?>
                   onchange="toggleSwitches(this.id)">
        </div>
    </div>
</div>
<script>
    function toggleSwitches(id) {
        if (id === 'omit' && $('#omit').is(':checked')) {
            $('#required').prop('checked', false);
        } else if (id === 'required' && $('#required').is(':checked')) {
            $('#omit').prop('checked', false);
        }
    }
</script>