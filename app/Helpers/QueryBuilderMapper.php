<?php

namespace App\Helpers;

use App\Core\DataMapper;
use App\Helpers\QueryBuilder;
use App\Interfaces\iQueryBuilder;

class QueryBuilderMapper extends QueryBuilder implements iQueryBuilder {
    private DataMapper $mapper;

    public function initMapper(DataMapper $mapper) {
        $this->mapper = $mapper;
        return $this;
    }

    public function write(): array|string {
        if ( !isset($this->mapper) ) { throw new \Exception("Mapper is not initialized"); }

        $data = $this->queryObject->write();

        return is_array($data) ? 
        $this->mapper->executeQuery( $data ) :
        $this->mapper->executeRaw( $data );
    }

    public function executeRaw(string $sql) {
        return $this->mapper->executeRaw($sql);
    }

    public function execute(): array|string {
        return $this->write();
    }
}