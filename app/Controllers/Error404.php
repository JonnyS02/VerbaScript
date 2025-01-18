<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class Error404 extends BaseController
{
    public function index(): string|RedirectResponse
    {
        return $this->viewMod('error404', 'error404');
    }
}
