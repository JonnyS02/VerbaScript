<?php

namespace App\Controllers\Elements;

class Variables extends ElementBaseController
{
    public function __construct()
    {
        parent::__construct(
            'Variable',
            'Variable',
            'Variablen'
        );
    }
}
