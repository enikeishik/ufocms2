<?php
spl_autoload_register(
    function ($class) {
        //BOOKMARK: use DIRECTORY_SEPARATOR instead of `/` on non *nix
        $class = str_replace('Ufocms\\', 'ufocms/', $class);
        $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
);
