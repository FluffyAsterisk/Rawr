<?php

declare(strict_types = 1);

namespace App\Caching;

use Psr\SimpleCache\CacheInterface;

class RedisCache implements CacheInterface {
    public function __construct(private readonly \Redis $redis) {}

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->redis->get($key);

        return $value === false ? $default : $value;
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        if ($ttl) {
            // TODO DateInterval Support!!!!!
            $ttl = is_int($ttl) ? $tll : 100;
        }
        return $this->redis->set($key, $value);
    }

    public function delete(string $key): bool
    {
        return $this->redis->del($key) ? true : false;
    }

    public function clear(): bool 
    {
        return $this->redis->flushAll();
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];

        foreach ($keys as $key) {
            array_push( $result, $this->get($key, $default) );
        }

        return $result;
    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        $r = true;

        foreach ($values as $key => $value) {
            $r = $r && $this->set($key, $value, $ttl);
        }

        return $r;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        return $this->redis->del($keys) ? true : false;
    }

    public function has(string $key): bool
    {
        return $this->redis->exists() ? true : false;
    }



}