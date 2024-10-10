<?php

namespace App\Migrations;

use App\Migrations\Schema;
use App\Exceptions\MigrationException;

class MigrationsManager
{
    private \PDO $db;
    private $migrations;
    private $batchName;

    public function __construct()
    {
        $config = parse_ini_file(__DIR__ . "/../../.env");
        $credentials = [];

        foreach ($config as $key => $value) 
        {
            if ( str_contains($key, 'DB') ) 
            {
                $credentials[$key] = $value;
            }
        }

        $this->db = $this->connectToDB($credentials);
    }

    private function connectToDB($creds) {
        extract($creds);

        $dsn = sprintf("%s:dbname=%s;user=%s;password=%s;", $DB_ENGINE, $DB_NAME, $DB_USERNAME, $DB_PASSWORD);
        $dsn = isset( $DB_HOST ) ? "{$dsn}host=$DB_HOST;" : $dsn;
        $dsn = isset( $DB_PORT ) ? "{$dsn}port=$DB_PORT;" : $dsn;

        try {
            $pdo = new \PDO($dsn);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
    }
    
    private function getLocalMigrations() {
        $migrations = glob( __DIR__ . "/../../migrations/*" );
        $migInst = [];
        $stmt = $this->db->query("SELECT MAX(`batch`) FROM `migrations`");$stmt->execute();
        $batchCount = $stmt->fetchAll()[0][0] + 1;

        foreach ( $migrations as $migration ) 
        {
            // echo "INSERT INTO `migrations` (name, batch) VALUES (" . basename($migration) . ', ' . $batchCount . ")\n";
            $stmt = $this->db->query("INSERT INTO `migrations` (`name`, `batch`) VALUES ('" . basename($migration) . '\', ' . $batchCount . ")");
            $migInst[] = require_once $migration;
        }

        $this->batchName = end($migrations);

        return $migInst;
    }

    private function migrationsTableExists() {
        try
        {
            $this->db->query("SELECT * FROM `migrations`");
            return true;
        } 
        catch (\PDOException $e)
        {
            if ( str_contains($e->getMessage(), 'migrations') ) 
            {
                return false;
            }

            throw $e;
        }
    }

    private function getExecutedMigrations()
    {
        if (!$this->migrationsTableExists()) 
        {
            throw new MigrationException("Table `migrations` doesn't exists. You may want to execute initial migration or create table manually.");
        }

        $stmt = $this->db->query("SELECT * FROM `migrations`");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function migrate() {
        foreach ($this->getLocalMigrations() as $migration) 
        {
            $migration->up();
        }

        $migrationsSQL = Schema::prepareMigrations();

        foreach ($migrationsSQL as $sql) {
            // $this->db->query($sql);
        }
    }
}