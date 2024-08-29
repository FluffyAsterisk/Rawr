<?php

namespace App\Core;

use App\Core\Model;

abstract class DataMapper {
    protected Model $mappedClass;

    abstract protected function getMappedClass(): Model;

    public function __construct(private \PDO $pdo, private \App\Helpers\Sanitizer $sanitizer, private \App\Helpers\QueryBuilder $queryBuilder) {
        $this->mappedClass = $this->getMappedClass();
    }

    public function getTableName() {
        return $this->mappedClass->tableName;
    }

    public function executeQuery(array $data) {
        $sql = $data[0];
        $values = $data[1];

        $stmt = $this->pdo->prepare($sql);

        $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->mappedClass::class);

        $stmt->execute($values);

        return $stmt->fetchAll();
    }

    public function executeRaw(string $sql) {
        $stmt = $this->pdo->prepare($sql);

        $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->mappedClass::class);

        $stmt->execute();

        return $stmt->fetchAll();
    }
}

// INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);