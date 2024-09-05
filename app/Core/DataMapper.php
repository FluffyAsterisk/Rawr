<?php

namespace App\Core;

class DataMapper {
    public function __construct(private \PDO $pdo) {}

    public function prepareQuery(array|string $data): \PDOStatement {
        if ( is_string($data) ) {
            $sql = $data;
            $values = [];
        } else {
            $sql = $data[0];
            $values = $data[1];
        }

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($values);
        
        return $stmt;
    }

    public function executeRaw(string $sql, string $fetchClass) {
        $stmt = $this->pdo->prepare($sql);

        $stmt->setFetchMode(\PDO::FETCH_CLASS, $fetchClass);

        $stmt->execute();

        return $stmt->fetchAll();
    }
}