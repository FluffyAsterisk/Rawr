<?php

namespace App\Queries;

use App\Interfaces\iQuery;
use App\Queries\Table;

abstract class Query implements iQuery
{
    protected array $tables;
    public function addTable(string $tableName, string|null $alias = null): Query
    {
        $this->tables[$tableName] = new Table($tableName, $alias);
        return $this;
    }

    public abstract function write(): array|string;

    protected function getMainTable(): Table {
        $k = array_keys($this->tables);
        return $this->tables[$k[0]];
    }
}