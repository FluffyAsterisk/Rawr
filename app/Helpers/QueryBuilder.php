<?php

namespace App\Helpers;

use App\Queries\Query;
use App\Queries\InsertQuery;
use App\Queries\SelectQuery;
use App\Queries\UpdateQuery;
use App\Queries\DeleteQuery;
use App\Interfaces\iQueryBuilder;

class QueryBuilder implements iQueryBuilder
{
    protected Query|null $queryObject;
    protected array $relations = [];

    public function insert()
    {
        if (isset($this->queryObject)) {
            throw new \Exception('Query has already been instantiated');
        }

        $this->queryObject = new InsertQuery();
        return $this;
    }

    public function select()
    {
        if (isset($this->queryObject)) {
            throw new \Exception('Query has already been instantiated');
        }

        $this->queryObject = new SelectQuery();
        return $this;
    }

    public function update()
    {
        if (isset($this->queryObject)) {
            throw new \Exception('Query has already been instantiated');
        }

        $this->queryObject = new UpdateQuery();
        return $this;
    }

    public function delete()
    {
        if (isset($this->queryObject)) {
            throw new \Exception('Query has already been instantiated');
        }

        $this->queryObject = new DeleteQuery();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function columns(array $columns): \App\Interfaces\iQueryConditions
    {
        $this->queryObject->columns($columns);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function equals($column, $value): \App\Interfaces\iQueryConditions
    {
        $this->queryObject->equals($column, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function greaterThan($column, $value): \App\Interfaces\iQueryConditions
    {
        $this->queryObject->greaterThan($column, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function lessThan($column, $value): \App\Interfaces\iQueryConditions
    {
        $this->queryObject->lessThan($column, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where(): \App\Interfaces\iQueryConditions
    {
        $this->queryObject->where();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setValues(array $values): \App\Interfaces\iQueryValues
    {
        $this->queryObject->setValues($values);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fullJoin(string $table, string $originField, string $targetField, array $targetColumns): \App\Interfaces\iSelectQuery
    {
        $this->queryObject->fullJoin($table, $originField, $targetField, $targetColumns);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function innerJoin(string $table, string $originField, string $targetField, array $targetColumns): \App\Interfaces\iSelectQuery
    {
        $this->queryObject->innerJoin($table, $originField, $targetField, $targetColumns);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function leftJoin(string $table, string $originField, string $targetField, array $targetColumns): \App\Interfaces\iSelectQuery
    {
        $this->queryObject->leftJoin($table, $originField, $targetField, $targetColumns);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rightJoin(string $table, string $originField, string $targetField, array $targetColumns): \App\Interfaces\iSelectQuery
    {
        $this->queryObject->rightJoin($table, $originField, $targetField, $targetColumns);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addTable(string $table): \App\Interfaces\iQuery
    {
        $this->queryObject->addTable($table);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function write(): array|string
    {
        $r = $this->queryObject->write();
        $this->queryObject = null;

        return $r;
    }
}