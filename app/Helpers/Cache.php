<?php

namespace App\Helpers;

use App\Enums\CacheType;
use App\Interfaces\CacheFrontInterface;
use Psr\SimpleCache\CacheInterface;

class Cache implements CacheInterface
{
    public function __construct(private CacheInterface $cachingBack, private CacheFrontInterface $cachingFront, CacheType $cacheType = CacheType::Encoded)
    {
        $this->setCacheType($cacheType);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $encData = $this->cachingBack->get($key);
        return $this->cachingFront->decode($encData);
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        $encValue = $this->cachingFront->encode($value);
        return $this->cachingBack->set($key, $encValue, $ttl);
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
        $encodedData = [];
        foreach ($values as $key => $value) {
            $encodedData[$key] = $this->cachingFront->encode($value);
        }

        return $this->cachingBack->setMultiple($encodedData, $ttl);
    }

    public function deleteMultiple(iterable $keys): bool
    {
        return $this->cachingBack->deleteMultiple($keys);
    }

    public function has(string $key): bool
    {
        return $this->cachingBack->has($key);
    }

    private function setCacheType(CacheType $type)
    {
        $this->cachingFront->setCacheType($type);
    }

}