<?php

$config = [
    'modules' => [
        'HotelData',
    ],
    'module_listener_options' => [
        'module_paths' => [
            dirname(__DIR__) . '/module',
            dirname(__DIR__) . '/vendor',
        ],
        'config_glob_paths' => [
            'autoload' => __DIR__ . '/autoload/{,*.}{global,local}.php',
            'autoload-env' => __DIR__ . '/autoload/env/' . $env . '{,*.local}.php',
        ],
    ],
];
$environmentConfig = 'config/env/' . $env . '.app.php';
if (is_readable($environmentConfig)) {
    $config = \Zend\Stdlib\ArrayUtils::merge($config, require $environmentConfig);
}

return $config;
