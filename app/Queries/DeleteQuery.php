<?php

namespace App\Queries;

use App\Queries\QueryConditions;
use App\Interfaces\iQuery;

class DeleteQuery extends QueryConditions implements iQuery
{
    protected string $table;

    public function write(): string
    {
        if (!isset($this->conditions)) {
            throw new \Exception("Condition should be set when creating delete query");
        }
        $query = sprintf("DELETE FROM `%s` WHERE %s", $this->table, implode(' AND ', $this->conditions));

        return $query;
    }
}