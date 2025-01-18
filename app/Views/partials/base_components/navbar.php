<nav class="navbar navbar-expand-md bg-body-tertiary mb-4 shadow">
    <div class="container-fluid align-content-center">
        <a class="navbar-brand mb-0 h1 highlight-text-color"
           href="<?= base_url(index_page()) . "/forms" ?>">VerbaScript</a>
        <?php if ($chosen_menu_item != "Anmeldung" && isset($session_role)) { ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-lg-0">
                    <li class="nav-item nav-link">
                        <a class="active remove-link-style <?= isset($chosen_menu_item) && $chosen_menu_item == "Formulare" ? "chosen" : "interactive-navbar" ?>"
                           aria-current="page" href="<?= base_url(index_page()) . "/forms" ?>">Formulare</a>
                    </li>
                    <?php if ($session_role > 2) { ?>
                        <li class="nav-item d-flex align-items-center d-none d-lg-inline-flex">
                            <div class="border-start mx-2" style="height: 20px;"></div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class=" active nav-link dropdown-toggle <?= isset($chosen_menu_item) && in_array($chosen_menu_item, ["Nutzer", "Einladungen"]) ? "chosen" : "interactive-navbar" ?>"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Personal
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item <?= isset($chosen_menu_item) && $chosen_menu_item == "Nutzer" ? "chosen" : "interactive-navbar" ?>"
                                       href="<?= base_url(index_page()) . "/users" ?>">Nutzer</a></li>
                                <li>
                                    <hr class='dropdown-divider'>
                                </li>
                                <li>
                                    <a class="dropdown-item <?= isset($chosen_menu_item) && $chosen_menu_item == "Einladungen" ? "chosen" : "interactive-navbar" ?>"
                                       href="<?= base_url(index_page()) . "/invitations" ?>">Einladungen</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($session_role > 1) { ?>
                        <li class="nav-item d-flex align-items-center d-none d-lg-inline-flex">
                            <div class="border-start mx-2" style="height: 20px;"></div>
                        </li>
                        <li class="nav-item nav-link">
                            <a class="active remove-link-style <?= isset($chosen_menu_item) && $chosen_menu_item == "Vorlagen" ? "chosen" : "interactive-navbar" ?>"
                               aria-current="page" href="<?= base_url(index_page()) . "/templates" ?>">Vorlagen</a>
                        </li>
                        <li class="nav-item nav-link">
                            <a class="active remove-link-style <?= isset($chosen_menu_item) && $chosen_menu_item == "Anordnung" ? "chosen" : "interactive-navbar" ?>"
                               aria-current="page" href="<?= base_url(index_page()) . "/order" ?>">Anordnung</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class=" active nav-link dropdown-toggle <?= isset($chosen_menu_item) && in_array($chosen_menu_item, ["Gruppen", "Variablen", "Selects", "Zahlen"]) ? "chosen" : "interactive-navbar" ?>"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Elemente
                            </a>
                            <ul class="dropdown-menu">
                                <?php
                                $navItems = [
                                    "Gruppen" => "groups",
                                    "Divider" => "",
                                    "Variablen" => "variables",
                                    "Selects" => "selects",
                                    "Zahlen" => "numbers"
                                ];
                                foreach ($navItems as $navItem => $url) {
                                    if ($navItem == "Divider") {
                                        echo "<li><hr class='dropdown-divider'></li>";
                                    } else {
                                        $url = base_url(index_page()) . "/{$url}";
                                        $activeClass = (isset($chosen_menu_item) && $chosen_menu_item == $navItem) ? "chosen" : "interactive-navbar";
                                        echo "<li><a class='dropdown-item {$activeClass}' href='{$url}'>{$navItem}</a></li>";
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item float-end nav-link">
                    <span class="interactive smooth-transition" data-bs-toggle="offcanvas"
                          data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                        <i style="font-size: unset" id="helpButton" class="fa-solid fa-book"></i> Anleitung
                    </span>
                    </li>
                    <li class="nav-item dropdown-center">
                        <a class="active nav-link dropdown-toggle <?= isset($chosen_menu_item) && $chosen_menu_item == "Profil" ? "chosen" : "interactive-navbar" ?>"
                           type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Konto
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item <?= isset($chosen_menu_item) && $chosen_menu_item == "Profil" ? "chosen" : "interactive-navbar" ?>"
                                   href="<?= base_url(index_page()) . "/profile" ?>"><i
                                            class="fa-regular fa-circle-user"
                                            style="font-size: unset"></i> <?= $session_username ?></a></li>
                            <li>
                                <hr class='dropdown-divider'>
                            </li>
                            <li><a class="dropdown-item interactive-navbar"
                                   href="<?= base_url(index_page()) . "/login" ?>"><i class="fa-solid fa-power-off"
                                                                                      style="font-size: unset"></i>
                                    Abmelden</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </div>
</nav>