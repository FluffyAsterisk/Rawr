<?php

namespace App\Queries;

use App\Queries\Query;
use App\Interfaces\iQuery;
use App\Interfaces\iQueryValues;

class InsertQuery extends Query implements iQuery, iQueryValues
{
    protected string $table;
    protected array $columns;
    protected array $values;
    protected int $placeholderCount = 1;

    public function setValues(array $values): InsertQuery
    {
        $this->values = $values;

        return $this;
    }

    public function write(): array
    {
        $values = $this->values;

        if (is_array($values[0])) {
            $placeholders = $this->insertMultiple($values);
            $columns = array_keys($values[0]);
        } else {
            $placeholders = $this->insertOne($values);
            $columns = array_keys($values);
        }

        $query = sprintf(
            "INSERT INTO `%s` (%s) VALUES %s %s",

            $this->table,
            implode(', ', $columns),
            $placeholders[0],
            isset($this->conditions) ? " WHERE " . implode(' AND ', $this->conditions) : '',
        );

        return [$query, $placeholders[1]];
    }

    private function insertOne($values)
    {
        $r = '';
        $v = [];

        foreach ($values as $key => $value) {
            $p = ':' . $this->placeholderCount . 'v';
            $r = $r . $p . ', ';
            $v[$p] = $value;

            $this->placeholderCount += 1;
        }

        $r = rtrim($r, ', ');

        return ['(' . $r . ')', $v];
    }

    private function insertMultiple($columnsArray)
    {
        $r = '';
        $v = [];

        foreach ($columnsArray as $cArr) {
            $p = $this->insertOne($cArr);
            $v = array_merge($v, $p[1]);
            $r .= $p[0] . ', ';
        }

        $r = rtrim($r, ', ');

        return [$r, $v];
    }
}