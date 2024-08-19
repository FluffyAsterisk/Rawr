<?php


namespace App\Interfaces;

interface CacheSystem {
    public function save();
    public function load();
}