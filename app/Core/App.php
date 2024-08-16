<?php

namespace App\Core;

class App {
    private $PARAMS;
    private const PROJECT_ROOT = __DIR__.'/../../';
    private const VIEWS_PATH = __DIR__.'/../../resources/views/';
    private const CACHE_PATH = __DIR__.'/../../cache/';

    public function __construct(private \App\Helpers\Sanitizer $sanitizer) {}

    public function base_path() {
        return self::PROJECT_ROOT;
    }

    public function views_path() {
        return self::VIEWS_PATH;
    }

    public function cache_path() {
        return self::CACHE_PATH;
    }

    public function db_cred() {
        return $this->PARAMS;
    }

    public function loadConfig($config): void {
        $config = parse_ini_file($config);
        $sanitizer = $this->sanitizer;

        foreach ($config as $key => $value) {
            $key = $sanitizer->sanitizeString($key);
            $value = $sanitizer->sanitizeString($value);
            $this->PARAMS[$key] = $value;
        }
    }
}
