<?php

namespace App\Core;
use App\Helpers\Template;
use App\Exceptions\MissingTemplateException;

class View {
	
    public static function render($file, $data=[]) {
        try {
	        Template::render($file, $data);
        } catch (MissingTemplateException $e) {
            echo($e->getMessage());
        }
    }

}
