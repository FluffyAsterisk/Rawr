<?php

namespace App\Helpers;

use Psr\SimpleCache\CacheInterface;

class Cache implements CacheInterface {
    public function __construct(private \App\Interfaces\CacheBackInterface $cachingBack, private \App\Interfaces\CacheFrontInterface $cachingFront) {}

    public function get(string $key, mixed $default = null): mixed
    {
        $encData = $this->cachingBack->load($key);
        return $this->cachingFront->decode($encData);
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