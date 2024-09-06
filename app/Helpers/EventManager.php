<?php

namespace App\Helpers;

class EventManager {
    private array $events = [];

    public function registerEvent(string $type, callable $callback) {
        if ( in_array($type, $this->events) ) { $this->events[$type] = []; }

        $this->events[$type][] = $callback;
    }

    public function removeEvent(string $type, callable $callback) {
        if ( in_array($type, $this->events) ) {
            if ( $key = array_search($callback, $this->events[$type]) ) {
                unset($this->events[$type][$key]);
            }
        }
    }

    public function notify(string $type, array $data = []) {
        if ( in_array($type, $this->events) ) { return; }

        foreach ($this->events[$type] as $callback) {
            $callback($data);
        }
    }
}