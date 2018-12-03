<?php

use Zend\Loader\AutoloaderFactory;

include 'vendor/zendframework/zendframework/library/Zend/Loader/AutoloaderFactory.php';

AutoloaderFactory::factory([
    'Zend\Loader\StandardAutoloader' => [
        'autoregister_zf' => true,
        'namespaces' => [
            'HotelData' => 'module/HotelData/src/HotelData',
            'HotelDataTest' => 'module/HotelData/test/HotelData',
        ],
    ],
]);
