<?php

namespace App\Queries;

use App\Interfaces\iQuery;

abstract class Query implements iQuery {
    protected string $table;
    public function setTable(string $tableName): Query {
        $this->table = $tableName;
        return $this;
    }

    public abstract function write(): array|string;
}