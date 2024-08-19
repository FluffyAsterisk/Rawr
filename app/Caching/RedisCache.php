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
        $encData = $this->cachingFront->encode($data);
        return $this->cachingBack->save($key, $encData);
    }

    public function delete(string $key): bool
    {
        return $this->cachingBack->delete($key);
    }

    public function clear(): bool 
    {
        
    }

    public function getMultiple(iterable $keys, mixed $default = null): is_iterable
    {

    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {

    }

    public function deleteMultiple(iterable $keys): bool
    {

    }

    public function has(string $key): bool
    {

    }



}