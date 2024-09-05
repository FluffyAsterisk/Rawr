<?php

namespace App\Interfaces;

use App\Interfaces\iQuery;

interface iQueryValues extends iQuery {
    public function setValues(array $values): iQueryValues;
}