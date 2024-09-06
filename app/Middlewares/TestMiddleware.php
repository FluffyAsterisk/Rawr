<?php

namespace App\Middlewares;

use App\Core\Middleware;
use App\Helpers\Request;

class TestMiddleware extends Middleware {
    public function __construct(private Request $request) {}

    public function handle() {
        $r = $this->request->capture();

        if ( str_contains($r['URI'], 'controll') ) 
        {
            $this->fail("URI should not contain 'controll' substring", 500);
        }

        $this->pass();
    }
}