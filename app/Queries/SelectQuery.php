<?php

namespace App\Queries;

use App\Queries\Column;
use App\Queries\QueryConditions;
use App\Interfaces\iSelectQuery;

class SelectQuery extends QueryConditions implements iSelectQuery
{
    protected array $joins;

    public function columns(array $columns, string|null $targetTable = null): Query
    {
        $cls = [];
        foreach ($columns as $key => $value) {
            if ( is_int($key) ) 
            {
                $cls[$value] = new Column($value);
            } 
            else 
            {
                $cls[$key] = new Column($key, $value);
            }
        }

        if (!$targetTable) 
        {
            ( $this->getMainTable() )->setColumns($cls);
        } else {
            $this->tables[$targetTable]->setColumns($cls);
        }

        return $this;
    }

    public function leftJoin(string $table, string $originField, string $targetField, array $targetColumns): SelectQuery
    {

        $this->joins[] = $this->handleJoin('LEFT', $table, $originField, $targetField, $targetColumns);
        return $this;
    }

    public function rightJoin(string $table, string $originField, string $targetField, array $targetColumns): SelectQuery
    {
        $this->joins[] = $this->handleJoin('RIGHT', $table, $originField, $targetField, $targetColumns);
        return $this;
    }

    public function innerJoin(string $table, string $originField, string $targetField, array $targetColumns): SelectQuery
    {
        $this->joins[] = $this->handleJoin('INNER', $table, $originField, $targetField, $targetColumns);
        return $this;
    }

    public function fullJoin(string $table, string $originField, string $targetField, array $targetColumns): SelectQuery
    {
        $this->joins[] = $this->handleJoin('FULL', $table, $originField, $targetField, $targetColumns);
        return $this;
    }

    public function write(): string
    {
        $columns = [];
        $conditions = [];

        foreach ($this->tables as $table) {
            $columns = array_merge($columns, $table->getColumns());
            $conditions = array_merge( $conditions, $table->getConditions() );
        }

        $query = sprintf(
            "SELECT %s FROM `%s` %s %s",
            implode(', ', $columns),
            $this->getMainTable()->getName(),
            isset($this->joins) ? implode(' ', $this->joins) : '',
            $conditions ? " WHERE " . implode(" AND ", $conditions) : '',
        );

        return $query;
    }

    private function handleJoin(string $join, string $table, string $originField, string $targetField, array $targetColumns): string
    {
        $this->addTable($table);
        $this->columns($targetColumns, $table);

        $mT = $this->getMainTable();

        // Decides, which commas should be used : ` or '
        return sprintf("%s JOIN %s ON %s = %s",
        $join, 
        isset($this->tables[$table]) ? "`$table`" : "'$table'",
        $mT->getAlias() ? "'{$mT->getAlias()}'.`$originField`" : "`{$mT->getName()}`.`$originField`",
        isset($this->tables[$table]) ? "`{$this->tables[$table]->getName()}`.`$targetField`" : "'{$this->tables[$table]->getName()}'.`$targetField`",
        );
    }
}