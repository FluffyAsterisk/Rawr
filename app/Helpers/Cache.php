<?php

namespace App\Helpers;

use Psr\SimpleCache\CacheInterface;

class Cache implements CacheInterface {
    public function __construct(private CacheInterface $cachingBack, private \App\Interfaces\CacheFrontInterface $cachingFront) {}

    public function get(string $key, mixed $default = null): mixed
    {
        $encData = $this->cachingBack->get($key);
        return $this->cachingFront->decode($encData);
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        $encValue = $this->cachingFront->encode($value);
        return $this->cachingBack->set($key, $encValue);
    }

    public function delete(string $key): bool
    {
        return $this->cachingBack->delete($key);
    }

    public function clear(): bool 
    {
        return $this->cachingBack->clear();
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $res = [];
        $encData = $this->cachingBack->getMultiple($keys, $default);

        foreach ($encData as $key => $value) {
            $res[$key] = $this->cachingFront->decode($value);
        }

        return $res;
    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        $cachhingFront = $this->cachingFront;

        $encDataGen = (function() use ($values, $cachhingFront) {
            foreach ($values as $key => $value) {
                yield $key => $cachhingFront->encode($value);
            }
        })();

        return $this->cachingBack->setMultiple($encDataGen, $ttl);
    }

    public function deleteMultiple(iterable $keys): bool
    {
        return $this->cachingBack->deleteMultiple($keys);
    }

    public function has(string $key): bool
    {
        return $this->cachingBack->has($key);
    }


}