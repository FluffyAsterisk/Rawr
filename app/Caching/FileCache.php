<?php

namespace App\Caching;

use Psr\SimpleCache\CacheInterface;
use App\Exceptions\CacheException;

class FileCache implements CacheInterface {
    public function __construct(private \App\Core\App $app, private \App\Helpers\Sanitizer $sanitizer) {}

    public function get(string $key, mixed $default = null): string|null {
        $filepath = $this->app->cache_path() . $key;

        if ( !file_exists($filepath) ) {
            return $default;
        };
        
        if (!$this->isExpired($key)) {
            return $this->hasExpire($key) ? $this->readExceptFirst($filepath) : file_get_contents($filepath);
        }
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool {
        $filepath = $this->app->cache_path() . $key;

        if ( isset($ttl) ) {
            $ttl = is_int($ttl) ? time() + $ttl : ( new \DateTime() )->add($ttl)->getTimestamp();
            $value = $this->sanitizer->wrapString($ttl) . "\n" . $value;
        }

        return file_put_contents($filepath, $value);
    }

    public function delete(string $key): bool {
        $filepath = $this->app->cache_path().$key;

        return unlink($filepath);
    }

    public function clear(): bool {
        $cache_dir = $this->app->cache_path();
        $cache_files = scandir( $cache_dir );
        $r = true;

        foreach (glob($cache_dir.'*') as $file) {
            $r = $r && unlink($file);
        }

        return $r;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
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
        $r = true;

        foreach ($keys as $key) {
            $r = $r && $this->delete($key);
        }

        return $r;
    }

    public function has(string $key) : bool {
        return file_exists( $this->app->cache_path() . $key ) && !$this->isExpired($key);
    }

    private function isExpired($key): bool {
        $filepath = $this->app->cache_path() . $key;

        if ( file_exists($filepath) ) {
            $f = fopen($filepath, 'r');
            $firstLine = fgets( $f );
            $ttl = [];

            fclose($f);

            $s = $this->sanitizer->getWrapSmbl();
            preg_match('~^'. $s .'\d+' . $s . '$~m', $firstLine, $ttl);

            if ($ttl) {
                $ttl = intval( $this->sanitizer->unwrapString( $ttl[0] ) );

                return $ttl < time();
            }

            return false;
        }
    }

    private function hasExpire($key): bool {
        $s = $this->sanitizer->getWrapSmbl();
        $fl = $this->getFirstLine($key);
        return preg_match('~^'. $s .'\d+' . $s . '$~m', $fl);
    }

    private function getFirstLine($key):string|bool {
        $filepath = $this->app->cache_path() . $key;

        if ( file_exists($filepath) ) {
            $f = fopen($filepath, 'r');
            $firstLine = fgets( $f );
            fclose($f);
            
            return $firstLine;
        }

        return false;
    }

    // Reads all file except first line
    private function readExceptFirst($filepath): string|bool {
        $f = fopen($filepath, 'r');
        $file = "";
        $switch = 1;

        if ($f) {
            while (( $line = fgets($f) ) !== false) {
                if ($switch) {
                    $switch = 0;
                    continue;
                }

                $file = $file . $line;
            }

            fclose($f);
        }

        return $file ?? false;
    }

}
