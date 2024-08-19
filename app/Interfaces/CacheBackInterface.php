<?php

namespace App\Interfaces;

interface CacheBackInterface {
    public function set(string $key, string $data, \DateInterval|int|null $ttl = null): bool;
    public function get(string $key): string;
    public function delete(string $key): bool;
}