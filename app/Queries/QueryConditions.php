<?php

namespace App\Queries;

use App\Queries\Query;
use App\Interfaces\iQueryConditions;

abstract class QueryConditions extends Query implements iQueryConditions {
    protected $conditions;

    public function where(): iQueryConditions {
        $this->conditions = [];
        return $this;
    }

    public function greaterThan($column, $value): iQueryConditions {
        $this->isWhereInst();

        $this->conditions[] = "$column > $value";
        return $this;
    }

    public function lessThan($column, $value): iQueryConditions {
        $this->isWhereInst();

        $this->conditions[] = "$column < $value";
        return $this;
    }

    public function equals($column, $value): iQueryConditions {
        $this->isWhereInst();

        $this->conditions[] = "$column = $value";
        return $this;
    }

    private function isWhereInst() {
        if ($this->conditions === null) { throw new \Exception('Where statement is not initiated'); }
    }
}
