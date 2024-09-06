<?php

namespace App\Core;
use App\Exceptions\MiddlewareException;

abstract class Middleware {
    private Middleware $next;
    private Middleware $last;

    abstract public function handle();

    public function setNext(Middleware $next) {
        if ( !isset($this->last) ) 
        {
            $this->next = $next;
            $this->last = $next;
        } 
        else 
        {
            $this->last->next = $next;
        }
    }

    protected function pass() {
        isset($this->next) ? $this->next->handle() : true;
    }

    protected function fail($message, $code) {
        throw new MiddlewareException($message, $code);
    }
}