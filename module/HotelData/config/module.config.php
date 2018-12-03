<?php

return [
    'console' => include 'console.config.php',
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'controllers' => [
        'invokables' => [
        ],
        'factories' => [
            'HotelData\Controller\ConsoleConvert' => \HotelData\Controller\Factory\ConsoleConvertControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'HotelData\Parser\ParseStrategy' => \HotelData\Parser\ParseStrategy::class,
            'HotelData\DataFilter' => \HotelData\Filter\DataFilter::class,
        ],
        'factories' => [
            'HotelData\Service\FileConverter' => \HotelData\Service\Factory\FileConverterFactory::class,
        ],
        'abstract_factories' => [
        ],
        'aliases' => [
        ],
    ],
    'listeners' => [
    ]
];
