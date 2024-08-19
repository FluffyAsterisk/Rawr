<?php

namespace App\Core;

class App {
    private $PARAMS;
    private const PROJECT_ROOT = __DIR__.'/../../';
    private const VIEWS_PATH = __DIR__.'/../../resources/views/';
    private const CACHE_PATH = __DIR__.'/../../cache/';

    public function __construct(private \App\Helpers\Sanitizer $sanitizer) {}

    public function base_path(): string {
        return self::PROJECT_ROOT;
    }

    public function views_path(): string {
        return self::VIEWS_PATH;
    }

    public function cache_path(): string {
        return self::CACHE_PATH;
    }

    public function db_cred(): array {
        $this->filterParams("DB");
    }

    public function redis_cred(): array {
        $this->filterParams("REDIS");
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

    private function filterParams(string $str): array {
        return array_filter($this->PARAMS, function ($key) {
            if ( str_contains($key, $str) ) { return $key; }
        }, ARRAY_FILTER_USE_KEY);
    }
}
