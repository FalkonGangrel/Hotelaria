<?php

namespace App\Core;

class BaseController {
    protected function view(string $path, array $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../Views/' . $path . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "View '{$path}' não encontrada.";
        }
    }
}
