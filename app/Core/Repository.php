<?php

namespace App\Core;

use App\Helpers\QueryBuilder;
use App\Core\DataMapper;

class Repository extends QueryBuilder {
    protected string $table;
    protected array $objects = [];
    public function __construct(protected DataMapper $mapper) {
        $modelName = (new \ReflectionClass($this::class))->getShortName();
        $modelName = str_replace('Repository', '', $this->pluralize($modelName));
        $this->table ??= strtolower($modelName);
    }

    public function insert() {
        return parent::insert()->addTable($this->table);
    }

    public function select() {
        return parent::select()->addTable($this->table);
    }

    public function update() {
        return parent::update()->addTable($this->table);
    }
    
    public function delete() {
        return parent::delete()->addTable($this->table);
    }

    public function leftJoin(string $table, string $originField, string $targetField, array $targetColumns): \App\Interfaces\iSelectQuery
    {
        // ToDo resolve table relation
        return parent::leftJoin($table, $originField, $targetField, $targetColumns);
    }

    public function write(): array|string {
        $modelName = str_replace( 'Repository', '', $this::class );
        $data = parent::write();
        $result = $this->mapper->executeQuery($data, $modelName);
        $this->objects = $result;

        return $this->objects;
    }

    public function execute(): array|string {
        return $this->write();
    }

    private function pluralize(string $word): string {
        $lastLetter = strtolower( $word[strlen($word) - 1] );

        return match ($lastLetter) {
            'y' => substr($word, 0, -1) . 'ies',
            's' => "{$word}es",
            default => "{$word}s",
        };
    }
}