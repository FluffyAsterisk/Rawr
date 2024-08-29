<?php

namespace App\Queries;

use App\Queries\QueryConditions;
use App\Interfaces\iQueryConditions;
use App\Interfaces\iQueryValues;

class UpdateQuery extends QueryConditions implements iQueryConditions, iQueryValues {
    protected string $table;
    protected array $values;
    protected array $conditions;
    protected int $placeholderCount = 1;

    public function setValues(array $values): UpdateQuery {
        $this->values = $values;
        return $this;
    }

    public function write(): array {
        $placeholders = $this->createPlaceholders($this->values);

        if ( !isset($this->conditions) ) { throw new \Exception('UPDATE query is not allowed without condition'); }

        $query = sprintf( 
            "UPDATE `%s` SET %s %s",

            $this->table, 
            $placeholders[0],
            isset($this->conditions) ? " WHERE " . implode(' AND ', $this->conditions) : '',
        );

        return [$query, $placeholders[1]];
    }

    private function createPlaceholders(array $values) {
        $r = '';
        $v = [];

        foreach ($values as $key => $value) {
            $p = ":" . $this->placeholderCount . 'v';
            $r .= "$key = " . $p . ', ';
            $v[$p] = $value;

            $this->placeholderCount += 1;
        }

        $r = rtrim($r, ', ');

        return [$r, $v];
    }
}