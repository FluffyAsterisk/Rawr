<?php

namespace App\Queries;

use App\Queries\QueryConditions;
use App\Interfaces\iSelectQuery;

class SelectQuery extends QueryConditions implements iSelectQuery {
    protected string $table;
    protected array $columns;
    protected string $join;

    public function columns(array $columns): Query {
        $this->columns = $columns;
        return $this;
    }

    public function leftJoin(string $table, string $originField, string $targetField): SelectQuery {
        $this->join = $this->genJoin('LEFT', $table, $originField, $targetField);
        return $this;
    }

    public function rightJoin(string $table, string $originField, string $targetField): SelectQuery {
        $this->join = $this->genJoin('RIGHT', $table, $originField, $targetField);
        return $this;
    }

    public function innerJoin(string $table, string $originField, string $targetField): SelectQuery {
        $this->join = $this->genJoin('INNER', $table, $originField, $targetField);
        return $this;
    }

    public function fullJoin(string $table, string $originField, string $targetField): SelectQuery {
        $this->join = $this->genJoin('FULL', $table, $originField, $targetField);
        return $this;
    }


    public function write(): string {
        $query = sprintf( 
            "SELECT %s FROM `%s` %s %s",

            implode(', ', $this->columns),
            $this->table,
            isset($this->join) ? $this->join : '',
            isset($this->conditions) ? " WHERE " . implode(' AND ', $this->conditions) : '',
        );

        return $query;
    }

    private function genJoin(string $join, string $table, string $originField, string $targetField): string {
        return sprintf("%s JOIN `%s` ON %s.%s = %s.%s", $join, $table, $this->table, $originField, $table, $targetField);
    }
}