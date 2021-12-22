<?php

namespace App\Modalku;

use App\Modalku\Modalku;

class ModalkuRepository{
    protected $modalku;

    public function __construct()
    {
        $this->modalku = new Modalku();
    }
}