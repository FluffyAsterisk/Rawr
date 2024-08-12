<?php

namespace App\Core;
use App\Helpers\Template;

class View {
	
    public static function render($file, $data=[]) {
	Template::render($file, $data);
    }

}
