<?php

namespace App\Helpers;

class Loger {
    public function prettyPrint($value) {
        print_r('<pre>');
        print_r($value);
        print_r('</pre>');
    }
}
