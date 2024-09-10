<?php

namespace App\Migrations;
use App\Migrations\Blueprint;

class Schema {
    private static array $tables;

    public static function create(string $name, callable $callback) {
        $o = new Blueprint($name);
        $callback($o);
        self::$tables[] = $o;
    }

    public static function prepareMigrations(): array {
        return array_map(function($table) { return $table->build(); }, self::$tables);
    }

    public static function dropIfExists($tableName) {
        $o = new class {
            public function build() {
                return "DROP TABLE IF EXISTS `{$this->tableName}`";
            }
        };

        $o->tableName = $tableName;

        self::$tables[] = $o;
    }
}