<?php

namespace App\Interfaces;

use App\Interfaces\iQuery;

interface iQueryConditions extends iQuery {
    public function where(): iQueryConditions; 

    public function greaterThan($column, $value): iQueryConditions; 

    public function lessThan($column, $value): iQueryConditions; 

    public function equals($column, $value): iQueryConditions; 
}