<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title highlight-text-color" id="offcanvasExampleLabel">VerbaScript Anleitung</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="accordion" id="accordionPanelsStayOpenExample">
            <?php $items = [
                ['title' => 'Formulare', 'content' => 'm_formular.php']
            ];

            if ($session_role > 2) {
                $items[] = ['title' => 'Nutzer', 'content' => 'm_user.php'];
                $items[] = ['title' => 'Einladungen', 'content' => 'm_invitation.php'];
            }

            if ($session_role > 1) {
                $items[] = ['title' => 'Vorlagen', 'content' => 'm_template.php'];
                $items[] = ['title' => 'Anordnung', 'content' => 'm_order.php'];
                $items[] = ['title' => 'Gruppen', 'content' => 'm_group.php'];
                $items[] = ['title' => 'Variablen', 'content' => 'm_variable.php'];
                $items[] = ['title' => 'Selects', 'content' => 'm_select.php'];
                $items[] = ['title' => 'Zahlen', 'content' => 'm_number.php'];
            }
            $items[] = ['title' => 'Profil', 'content' => 'm_profile.php'];
            foreach ($items as $item) {
                include 'item.php';
            } ?>
        </div>
        <div class="px-3 mt-4 mb-3">
            Weitere Hinweise erscheinen, wenn Sie mit der Maus über bestimmte Elemente fahren:
            <img src="<?= base_url() ?>imgs/hint_example.jpg" class="img-fluid mt-2 border rounded-3"
                 alt="hint example">
        </div>
    </div>
</div>