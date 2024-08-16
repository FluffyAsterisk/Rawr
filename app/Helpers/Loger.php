<?php

namespace App\Helpers;

class Logger {
    public function prettyPrint($value) {
        print_r('<pre>');
        print_r($value);
        print_r('</pre>');
    }
}