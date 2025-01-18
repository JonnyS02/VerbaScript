<div class="card shadow mx-auto <?= (isset($form['global_ai']) && $form['global_ai']) ? "mt-4" :"mt-5"?> <?= isset($is_form_card) ? "" : "smaller-width" ?>" <?= isset($is_login_form) ? 'style="max-width: 30rem"' :"" ?>>
    <div class="card-header">
        <h1 class="display-6"><?= $headline ?></h1>
    </div>
    <div class="card-body">
        <?php include __DIR__ . "/../" . $body ?>
    </div>
</div>