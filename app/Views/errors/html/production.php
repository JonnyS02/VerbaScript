<html lang="de" data-bs-theme="dark">
<?php include APPPATH . 'Views/partials/base_components/head.php' ?>
    <body>
    <nav class="navbar navbar-expand-md bg-body-tertiary mb-4 shadow">
        <div class="container-fluid align-content-center">
            <a class="navbar-brand mb-0 h1 highlight-text-color"
               href="<?= base_url(index_page()) ?>/formular">VerbaScript</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-lg-0">
                    <li class="nav-item nav-link">
                        <a class="active remove-link-style interactive-navbar" aria-current="page"
                           href="<?= base_url(index_page()) ?>/forms">Formulare</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
        <div class="container">
            <div class="">
                <h1 class="display-1 text-center m-5">Entschuldigung</h1>
                <p class="h3 text-center">Es ist ein Fehler aufgetreten.</p>
                <p class="h3 text-center">Bitte navigieren Sie auf eine andere Seite.</p>
                <p class="h3 text-center">Wenn der Fehler weiterhin besteht, melden Sie sich ab und wieder an.</p>
                <div class="d-flex align-items-center mt-4">
                    <button class="btn btn-primary m-auto" onclick="window.history.back();">Zurück</button>
                </div>
            </div>
            <footer class="d-flex justify-content-between pt-3 mt-5 border-top">
                <p class="text-body-secondary">© 2024 Jonathan Stengl</p>
                <a class="text-body-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom"
                   href="https://www.freepik.com/" data-bs-original-title="Quelle des Webseiten Icons: www.freepik.com"
                   aria-describedby="tooltip240359">Icon by Freepik</a>
            </footer>
            <script>
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            </script>
        </div>

        <div class="tooltip bs-tooltip-auto fade" role="tooltip" id="tooltip240359" data-popper-placement="bottom"
             style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(1341px, 167px);">
            <div class="tooltip-arrow" style="position: absolute; left: 0px; transform: translate(94px, 0px);"></div>
            <div class="tooltip-inner">Quelle des Webseiten Icons: www.freepik.com
            </div>
        </div>
    </body>
</html>
