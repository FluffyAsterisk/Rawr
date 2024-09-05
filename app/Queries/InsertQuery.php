<?php

namespace App\Queries;

use App\Queries\Query;
use App\Interfaces\iQuery;
use App\Interfaces\iQueryValues;

class InsertQuery extends Query implements iQuery, iQueryValues
{
    protected array $values;
    protected int $placeholderCount = 1;

    public function setValues(array $values): InsertQuery
    {
        $this->values = $values;

        $cls = [];

        $values = is_array($values) ? $values[0] : $values;

        foreach (array_flip($values) as $column) {
            $cls[$column] = new Column($column);
        }

        ($this->getMainTable())->setColumns($cls);

        return $this;
    }

    public function write(): array
    {
        $values = $this->values;
        $placeholders = (is_array($values[0])) ? $this->insertMultiple($values) : $this->insertOne($values);
        
        $query = sprintf(
            "INSERT INTO `%s` (%s) VALUES %s",

            ($this->getMainTable())->getName(),
            implode(', ', $this->getMainTable()->getColumnsStrings()),
            $placeholders[0],
        );

        return [$query, $placeholders[1]];
    }

    private function insertOne($values)
    {
        $r = '';
        $v = [];

        foreach ($this->getMainTable()->getColumnsStrings() as $value) {
            $p = ":{$this->placeholderCount}v";
            $r = "$r$p, ";
            $v[$p] = $values[$value];

            $this->placeholderCount += 1;
        }

        $r = rtrim($r, ', ');

        return ["($r)", $v];
    }

    private function insertMultiple($columnsArray)
    {
        $r = '';
        $v = [];

        foreach ($columnsArray as $cArr) {
            $p = $this->insertOne($cArr);
            $v = array_merge($v, $p[1]);
            $r .= "$p[0], ";
        }

        $r = rtrim($r, ', ');

        return [$r, $v];
    }
}