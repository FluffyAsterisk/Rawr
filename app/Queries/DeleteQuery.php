<?php

namespace App\Queries;

use App\Queries\QueryConditions;
use App\Interfaces\iQuery;

class DeleteQuery extends QueryConditions implements iQuery
{
    public function write(): string
    {
        $conditions = $this->getMainTable()->getConditions();

        if (!isset($conditions)) {
            throw new \Exception("Condition should be set when creating delete query");
        }

        $conditions = str_replace("'", "`", $conditions);


        $query = sprintf("DELETE FROM `%s` WHERE %s", $this->getMainTable()->getName(), implode(' AND ', $conditions));

        return $query;
    }
}