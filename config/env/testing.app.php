<?php

return [
    'module_listener_options' => [
        'config_cache_enabled' => true,
        'module_map_cache_enabled' => true,
        // Extend development environment
        'config_glob_paths' => [
            'autoload-env' => 'config/autoload/env/{development,testing}.php',
            'autoload-local' => 'configuration.php',
        ],
    ],
];
