<?php

namespace App\Interfaces;

use App\Core\DataMapper;
use App\Interfaces\iQueryConditions;
use App\Interfaces\iQueryValues;
use App\Interfaces\iSelectQuery;

interface iQueryBuilder extends iQueryConditions, iQueryValues, iSelectQuery {
    public function insert();
    public function select();
    public function update();
    public function delete();
}