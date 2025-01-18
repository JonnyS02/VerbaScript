<div class="accordion-item rounded-0">
    <h2 class="accordion-header">
        <button class="accordion-button <?= $item['title'] == $chosen_menu_item ? "" : "collapsed"?>" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-<?= $item['title'] ?? ""?>" aria-expanded="<?= $item['title'] == $chosen_menu_item ? "true" : "false"?>"
                aria-controls="panelsStayOpen-<?= $item['title'] ?? ""?>">
            <?= $item['title'] ?? ""?>
        </button>
    </h2>
    <div id="panelsStayOpen-<?= $item['title'] ?? ""?>" class="accordion-collapse collapse <?=  $item['title'] == $chosen_menu_item ? "show" : ""?>">
        <div class="accordion-body">
            <?php include 'content/' . $item['content'] ?? ""?>
        </div>
    </div>
</div>