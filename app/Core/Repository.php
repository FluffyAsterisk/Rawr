<?php

namespace App\Core;

use App\Helpers\QueryBuilder;
use App\Core\DataMapper;
use App\Queries\SelectQuery;

class Repository extends QueryBuilder {
    protected string $mainTableName;
    protected string $modelClass;
    protected array $usedModels;

    public function __construct(protected DataMapper $mapper) {
        $refl = new \ReflectionClass($this::class);
        $modelName = $refl->getShortName();
        $modelName = str_replace('Repository', '', $this->pluralize($modelName));
        $this->mainTableName = $this->table ?? strtolower($modelName);

        // Initializing model dependencies
        foreach ($refl->getMethods() as $method) {
            if ($method->class != $this::class) { break; }

            $method->invoke($this);
        }
    }

    public function insert() {
        return parent::insert()->addTable($this->mainTableName);
    }

    public function select() {
        return parent::select()->addTable($this->mainTableName);
    }

    public function update() {
        return parent::update()->addTable($this->mainTableName);
    }
    
    public function delete() {
        return parent::delete()->addTable($this->mainTableName);
    }

    public function write(): array|string {
        $data = $this->queryObject->write();

        $stmt = $this->mapper->prepareQuery($data);

        if (!$this->queryObject instanceof SelectQuery) {
            return $stmt->fetch();
        }
        if ( isset($this->usedModels) ) {
            return $this->resolveModels($stmt); 
        }

        return $this->resolveDefault($stmt);
    }

    public function execute(): array|string {
        return $this->write();
    }

    private function flattenArray($array) {
        $flat = [];

        foreach ($array as $arr) {
            if (is_array($arr)) {
                $flat = array_merge($flat, $this->flattenArray($arr));
            } else {
                $flat[] = $arr;
            }
        }

        return $flat;
    }

    private function resolveModels(\PDOStatement $stmt): array {
        $result = [];
        $models = [];
        $modelClasses = array_merge( [$this->getMainRepoClass()], $this->usedModels );

        $columns = array_map(function($table) { return $table->getColumnsArray(); }, $this->queryObject->tables);
        $columns = $this->flattenArray($columns);
        $aliases =  array_column($columns, 'alias', 'name');

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            foreach ($modelClasses as $m) {
                $models[] = new $m();
            }

            foreach ($row as $key => $value) 
            {
                $s = array_search($key, $aliases);

                foreach ($models as $model) 
                {
                    if ( property_exists($model, $key) && $value ) 
                    {
                        $model->$key = $value;
                        break;
                    } 
                    else if ($s && !isset($model->$s)) 
                    {
                        $model->$s = $value;
                        break;
                    }
                }
            }

            $models = array_filter($models, function($model) { return array_flip( (array) $model ); });

            if ( is_array( end($result) ) && end($result)[0] == $models[0] && count($models) != 1 )
            {
                $o = array_pop($result);
                $models = array_reverse($models);
                array_pop($models);
                $o[1] = array_merge($o[1], $models);
                $result[] = $o;
            }
            else if ( count($models) == 1 )
            {
                $result = array_merge($result, $models);
            } 
            else 
            {
                $models = array_reverse($models);
                $result[] = [array_pop($models), $models];
            }
            
            $models = [];

        }

        return $result;
    }

    // Basic resolve used in case there is no other models specified
    private function resolveDefault(\PDOStatement $stmt): array
    {
        $result = [];
        $columns = $this->queryObject->tables[$this->mainTableName]->getColumnsArray();
        $modelClass = $this->getMainRepoClass();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $m = new $modelClass();
            $other = new \stdClass();

            // Key - column key
            foreach ($row as $key =>$value) 
            {
                if ( property_exists($m, $key) ) 
                {
                    $m->$key = $value;
                } 
                else 
                {
                    $c = array_filter($columns, function($col) use ($key) { return $col->alias == $key; });

                    if ($c) 
                    {
                        $m->{$c[0]->name} = $value;
                    } 
                    else 
                    {
                        $other->$key = $value;
                    }
                }
            }

            if (is_array(end($result)) && $m == end($result)[0]) 
            {
                $o = array_pop($result);
                $o[1][] = $other;
                $result[] = $o;
            }
            else 
            {
                // If object has other object connected to it
                $result[] = !empty( array_flip((array) $other) ) ? [$m, [$other]] : $m;
            }
        }

        return $result;
    }

    private function getMainRepoClass(): string {
        if ( isset($this->modelClass) ) { return $this->modelClass; }
        $r = new \ReflectionClass($this);
        
        return "\App\Models\\" . str_replace('Repository', '', $r->getShortName());
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
