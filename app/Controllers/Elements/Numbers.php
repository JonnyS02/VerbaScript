<?php

namespace App\Controllers\Elements;

class Numbers extends ElementBaseController
{
    public function __construct()
    {
        parent::__construct(
            'Number',
            'Zahl',
            'Zahlen'
        );
    }
}
