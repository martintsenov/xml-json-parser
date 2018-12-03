<?php

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

if (getenv('REDIRECT_APPLICATION_ENV') && getenv('APPLICATION_ENV') === false) {
    putenv('APPLICATION_ENV=' . getenv('REDIRECT_APPLICATION_ENV'));
}

$env = getenv('APPLICATION_ENV');
$environments = ['production', 'testing', 'development'];
if (!in_array($env, $environments)) {
    $env = 'development';
    putenv('APPLICATION_ENV=development');
}

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
try {
    Zend\Mvc\Application::init(require 'config/application.config.php')->run();
} catch (\Throwable $e) {
    var_dump($e->getMessage());
    throw $e;
}
