<?php

namespace App\Core;

use App\Helpers\Template;

class View {
    public function __construct(private Template $template) {}
	
    public function render($file, $data=[], bool $caching = true) {
        try {
	        $this->template->init($file, $caching, 300);
        } catch (\App\Exceptions\MissingTemplateException $e) {
            echo($e->getMessage());
            die();
        }

        $this->template->render($data);
    }

}
