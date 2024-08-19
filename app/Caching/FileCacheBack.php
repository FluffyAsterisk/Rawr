<?php

namespace App\Caching;

use App\Interfaces\CacheBackInterface;
use App\Exceptions\CacheException;

class FileCacheBack implements CacheBackInterface {
    public function __construct(private \App\Core\App $app) {}

    public function set(string $key, string $data, \DateInterval|int|null $ttl = null): bool {
        $filepath = $this->app->cache_path() . $key;

        if ( isset($ttl) ) {
            $ttl = is_int($ttl) ? time() + $ttl : ( new \DateTime() )->add($ttl)->getTimestamp();
            $data = $ttl . "\n" . $data;
        }

        return file_put_contents($filepath, $data);
    }

    public function get(string $key): string {
        $filepath = $this->app->cache_path() . $key;

        if (!$this->isExpired($key)) {
            return $this->readExceptFirst($filepath);
        }

        throw new CacheException("{$key} doesn't exist in cache");
    }

    public function delete(string $key): bool {
        $filepath = $this->app->cache_path().$key;

        return unlink($filepath);
    }

    public function clear() {
        $cache_dir = $this->app->cache_path();
        $cache_files = scandir( $cache_dir );

        print_r("<pre>");
        foreach ($cache_files as $file) {
            // unlink($file);
            print_r($cache_dir . $file . "\n");
        }
        print_r("</pre>");

        return true;
    }

    private function isExpired($key): bool {
        $filepath = $this->app->cache_path() . $key;

        if ( file_exists($filepath) ) {
            $f = fopen($filepath, 'r');
            $firstLine = fgets( $f );
            $ttl = [];

            fclose($f);

            preg_match("~^\d+$~m", $firstLine, $ttl);

            if ($ttl) {
                $ttl = intval( $ttl[0] );

                return $ttl < time();
            }

            return false;
        }
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
