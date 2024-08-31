<?php

namespace App\Core;

class DataMapper {
    public function __construct(private \PDO $pdo) {}

    public function executeQuery(array|string $data, string $fetchClass) {
        if ( is_string($data) ) {
            $sql = $data;
            $values = [];
        } else {
            $sql = $data[0];
            $values = $data[1];
        }

        $stmt = $this->pdo->prepare($sql);
        print_r($sql);

        // $stmt->setFetchMode(\PDO::FETCH_CLASS, $fetchClass);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $stmt->execute($values);

        print_r("<pre>");
        print_r($stmt->fetchAll());
        print_r("</pre>");
        die();

        return $stmt->fetchAll();
    }

    public function executeRaw(string $sql, string $fetchClass) {
        $stmt = $this->pdo->prepare($sql);

        $stmt->setFetchMode(\PDO::FETCH_CLASS, $fetchClass);

        $stmt->execute();

        return $stmt->fetchAll();
    }
}