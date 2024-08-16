<?php

namespace App\Core;
use App\Helpers\Template;
use App\Exceptions\MissingTemplateException;

class View {
	
    public static function render($file, $data=[]) {
        try {
	        $template = Template::prepare($file, $data);
        } catch (MissingTemplateException $e) {
            echo($e->getMessage());
            die();
        }

		extract($data);
		require $template;
    }

}
