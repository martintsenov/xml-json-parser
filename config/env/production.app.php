<?php

return [
    'module_listener_options' => [
        'config_cache_enabled' => true,
        'module_map_cache_enabled' => true,
        'config_glob_paths' => [
            'autoload-env' => 'config/autoload/env/production.php',
            'autoload-local' => 'configuration.php',
        ],
    ],
];
