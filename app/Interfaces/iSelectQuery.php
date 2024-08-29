<?php

namespace App\Interfaces;

use App\Interfaces\iQueryConditions;

interface iSelectQuery extends iQueryConditions {
    public function leftJoin(string $table, string $originField, string $targetField): iSelectQuery;

    public function rightJoin(string $table, string $originField, string $targetField): iSelectQuery;

    public function innerJoin(string $table, string $originField, string $targetField): iSelectQuery;

    public function fullJoin(string $table, string $originField, string $targetField): iSelectQuery;
}