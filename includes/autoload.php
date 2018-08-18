<?php
spl_autoload_register(function($class) {
    $directories = [            // can be modified when the project grow
        '',
        'core/',
    ];
    $class = ltrim($class);
    foreach ($directories as $directory) {
        $path = dirname(__FILE__) . '/' . $directory . $class . '.php';
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
