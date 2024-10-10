<?php

namespace App\Core;

use App\Helpers\EventManager;
use App\Helpers\Template;

class View {
    public function __construct(private Template $template, private EventManager $eventManager) {}
	
    public function render($file, $data=[], bool $caching = true) {
        $caching = false;
        try {
	        $this->template->init($file, $caching, 300);
        } catch (\App\Exceptions\MissingTemplateException $e) {
            echo $e->getMessage();
            die();
        }

        $this->eventManager->notify('pageRendered', ['pageName' => $file]);

        $this->template->render($data);
    }

}
