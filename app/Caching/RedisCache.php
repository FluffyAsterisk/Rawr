<?php

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
            $ttl = is_int($ttl) ? $ttl : date_create('@0')->add($ttl)->getTimestamp();
        }

        return $this->redis->set($key, $value, $ttl);
    }

    public function delete(string $key): bool
    {
        return $this->redis->del($key) === 1;
    }

    public function clear(): bool 
    {
        return $this->redis->flushAll();
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];
        $values = $this->redis->mGet($keys);

        foreach ($keys as $i => $key) {
            $result[$key] = $values[$i];
        }

        return $result;
    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        $values = (array) $values;

        $result = $this->redis->mSet($values);

        if ($ttl !== null) {
            $ttl = is_int($ttl) ? $ttl : date_create('@0')->add($ttl)->getTimestamp();

            foreach (array_keys($values) as $key) {
                $this->redis->expire($key, (int) $ttl);
            }
        }

        return $result;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        $keys = (array) $keys;

        return $this->redis->del($keys) === count($keys);
    }

    public function has(string $key): bool
    {
        return $this->redis->exists($key) == 1;
    }

    
    public function ttl(string $key): int
    {
        return $this->redis->ttl($key);
    }

}