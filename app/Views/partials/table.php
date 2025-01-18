<?php if(isset($use_template_select)){ ?>
    <?php include  'template_select.php'; ?>
<?php } ?>
<label for="search" title="<?= $item_name_sg ?> suchen">
    <h1 class="display-6 mx-3 "><?= $headline ?> </h1>
</label>
<div class="form-group row mb-2 mx-1">
    <div class="input-group">
        <input type="text" class="form-control w-25 search text-truncate "
               title="<?= $item_name_sg ?> suchen"
               id="search"
               placeholder="🔍 <?= $item_name_sg ?>"
               onkeyup="searchItem(<?= $filter['column'] ?? "-1" ?>)"
            <?= empty($items) ? "disabled" : "" ?>>
        <?php if (isset($filter)) { ?>
            <select title="Nach <?= $filter['default']['name'] ?> filtern" id="filter"
                    onchange="searchItem(<?= $filter['column'] ?>)"
                    class="text-truncate form-select" <?= empty($items) ? "disabled" : "" ?>
                    aria-label="Table select filter">
                <option title="Gruppe" value="" selected><?= $filter['default']['name'] ?></option>
                <?php foreach ($filter['items'] as $item) { ?>
                    <option title="<?= $item['name'] ?>" value="<?= $item['name'] ?>"><?= $item['name'] ?></option>
                <?php } ?>
            </select>
        <?php } else{?>
            <input type="hidden" id="filter" value="">
        <?php } ?>
        <?php if (!isset($is_order_table) && !isset($group_table) && !isset($hide_add_button)) { ?>
            <a class="btn btn-primary border-radius-right <?= isset($disable_add) ? "disabled" : "" ?>"
               title="<?= $item_name_sg ?> hinzufügen"
               href="<?= base_url(index_page()) . "/insert" . $href ?>">
                Hinzufügen
            </a>
        <?php } elseif (isset($group_table)) { ?>
            <button type="button"
                    class="btn btn-primary border-radius-right"
                    data-bs-toggle="modal"
                    title="<?= $item_name_sg ?> hinzufügen"
                <?= isset($disable_add) ? "disabled" : "" ?>
                    data-bs-target="#groupModal" onclick="resetModal()">
                Hinzufügen
            </button>
        <?php } ?>
    </div>
</div>
<div class="overflow-hidden rounded-3 border mb-4" >
    <div class="div-shadow">
        <div class="table-height">
            <table class="table m-0 table-striped" id="table">
                <thead>
                <tr>
                    <?php foreach ($columns as $column) { ?>
                        <th title="<?= $column['title'] ?>"><?= $column['name'] ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php include $table_body; ?>
                </tbody>
            </table>
            <?php if (empty($items)) { ?>
                <div class="w-100 text-center fw-bold my-2 text-white">
                    Keine <?= $item_name_pl ?> registriert
                </div>
            <?php } ?>
            <div class="w-100 text-center fw-bold my-2 text-white d-none" id="noItems">
                Keine <?= $item_name_pl ?> gefunden
            </div>
        </div>
    </div>
</div>

<script>
    function searchItem(column) {
        var filterInput = true;
        var searchInput = true;
        var filterValue = $('#filter').val().toLowerCase();
        var searchValue = $('#search').val().toLowerCase();
        var rowsVisible = false;
        if (filterValue === "") {
            filterInput = false;
        }
        if (searchValue === "") {
            searchInput = false;
        }
        if (!filterInput && !searchInput) {
            $('#table tbody tr').show();
            $('#noItems').addClass('d-none');
            $('#table tbody span').removeClass('highlight-text');
            return;
        }
        $('#table tbody tr').each(function () {
            var row = $(this);
            var searchCell = row.find('td').first();
            var filterCell = row.find('td').eq(column);
            var searchCellSpan = searchCell.find('span');
            var filterCellSpan = filterCell.find('span');
            var searchCellText = searchCellSpan.text().toLowerCase();
            var filterCellText = filterCellSpan.text().toLowerCase();
            var showRow = true;
            if (searchInput && !searchCellText.includes(searchValue)) {
                showRow = false;
            }
            if (filterInput && !filterCellText.includes(filterValue)) {
                showRow = false;
            }
            if (showRow) {
                row.show();
                if (searchInput) {
                    searchCellSpan.addClass('highlight-text');
                }
                if (filterInput) {
                    filterCellSpan.addClass('highlight-text');
                }
                if(!searchInput){
                    searchCellSpan.removeClass('highlight-text');
                }
                if(!filterInput){
                    filterCellSpan.removeClass('highlight-text');
                }
                rowsVisible = true;
            } else {
                row.hide();
                searchCellSpan.removeClass('highlight-text');
                filterCellSpan.removeClass('highlight-text');
            }
        });
        if (rowsVisible) {
            $('#noItems').addClass('d-none');
        } else {
            $('#noItems').removeClass('d-none');
        }
    }
</script>
