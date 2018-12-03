<?php

return [
    'router' => [
        'routes' => [
            'convert-data' => [
                'options' => [
                    'route' => 'data convert --file=',
                    'defaults' => [
                        'controller' => 'HotelData\Controller\ConsoleConvert',
                        'action' => 'convert'
                    ],
                ],
            ],
        ],
    ],
];
