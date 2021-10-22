<?php

namespace System\View\Traits;

use Exception;

trait HasViewLoader
{


    private $viewNameArray = [];

    private function viewLoader($dir)
    {
        $dir = trim( $dir, " .");
        $dir = str_replace(".", DIRECTORY_SEPARATOR, $dir);
        $baseViewDir = DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR;

        if (file_exists(dirname(__DIR__, 3) . $baseViewDir . $dir . ".blade.php")) {
            $this->registerView($dir);
            $content = htmlentities(file_get_contents(dirname(__DIR__, 3) . $baseViewDir . $dir . ".blade.php"));
            return $content;
        } else {
            throw new Exception("view not found at: System/viewLader");
        }
    }

    
    private function registerView($view)
    {
        array_push($this->viewNameArray, $view);
    }
}
