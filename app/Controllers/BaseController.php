<?php

class BaseController {
    protected function render($view, $data = [], $cssFiles = []) {
        extract($data);
        // Inclure les fichiers CSS si spécifiés
        foreach ($cssFiles as $cssFile) {
            echo "<link rel=\"stylesheet\" href=\"/public/css/{$cssFile}\">\n";
        }
        require_once __DIR__ . "/../Views/{$view}.php";
    }

    protected function redirect($url) {
        header("Location: {$url}");
        exit();
    }
}

?>
