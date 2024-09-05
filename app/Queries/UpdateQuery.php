<?php

namespace App\Queries;

use App\Queries\QueryConditions;
use App\Interfaces\iQueryConditions;
use App\Interfaces\iQueryValues;

class UpdateQuery extends QueryConditions implements iQueryConditions, iQueryValues
{
    protected array $values;
    protected int $placeholderCount = 1;

    public function setValues(array $values): UpdateQuery
    {
        $this->values = $values;
        $cls = [];

        foreach (array_flip($values) as $column) {
            $cls[$column] = new Column($column);
        }

        ($this->getMainTable())->setColumns($cls);

        return $this;
    }

    public function write(): array
    {
        $placeholders = $this->createPlaceholders($this->values);
        $conditions = [];

        foreach ($this->tables as $table) {
            $conditions = array_merge( $conditions, $table->getConditions() );
        }

        $conditions = str_replace("'", "`", $conditions);

        if (!$conditions) {
            throw new \Exception('UPDATE query is not allowed without condition');
        }

        $query = sprintf(
            "UPDATE `%s` SET %s %s",

            ($this->getMainTable())->getName(),
            $placeholders[0],
            " WHERE " . implode(' AND ', $conditions),
        );

        return [$query, $placeholders[1]];
    }

    private function createPlaceholders(array $values)
    {
        $r = '';
        $v = [];

        foreach ($values as $key => $value) {
            $p = ":{$this->placeholderCount}v";
            $r .= "`{$this->getMainTable()->getName()}`.`$key` = $p, ";
            $v[$p] = $value;

            $this->placeholderCount += 1;
        }

        $r = rtrim($r, ', ');

        return [$r, $v];
    }
}