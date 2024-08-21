<?php

namespace App\Core;

use App\Helpers\Template;

class View {
    public function __construct(private Template $template) {}
	
    public function render($file, $data=[]) {
        try {
	        $this->template->init($file, true, 300);
        } catch (\App\Exceptions\MissingTemplateException $e) {
            echo($e->getMessage());
            die();
        }

        $this->template->render($data);
    }

}
