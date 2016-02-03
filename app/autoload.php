<?php
spl_autoload_register(function ($class) {
    $filename = str_replace('\\', '/', $class);
    $path = __DIR__ . '/' . $filename . '.php';

    if (is_file($path)) {
        include($path);
    }
});
