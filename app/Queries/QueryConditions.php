<?php

namespace App\Queries;

use App\Queries\Query;
use App\Interfaces\iQueryConditions;

abstract class QueryConditions extends Query implements iQueryConditions
{
    protected $isConditions;

    public function where(): iQueryConditions
    {
        $this->isConditions = true;
        return $this;
    }

    public function greaterThan($column, $value, $tableName = null): iQueryConditions
    {
        $this->isWhereInst();
        
        $t = $tableName ?? array_keys($this->tables)[0];

        $this->tables[$t]->addCondition("$column > $value");

        return $this;
    }

    // Specify table name if condition relates to foreign table
    public function lessThan($column, $value, $tableName = null): iQueryConditions
    {
        $this->isWhereInst();
        
        $t = $tableName ?? array_keys($this->tables)[0];

        $this->tables[$t]->addCondition("$column < $value");

        return $this;

    }

    public function equals($column, $value, $tableName = null): iQueryConditions
    {
        $this->isWhereInst();
        
        $t = $tableName ?? array_keys($this->tables)[0];

        $this->tables[$t]->addCondition("$column = $value");

        return $this;

    }

    private function isWhereInst()
    {
        if (!$this->isConditions) {
            throw new \Exception('Where statement is not initiated');
        }
    }
}