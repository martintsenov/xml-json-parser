<?php

namespace HotelData\Service\Factory;

use HotelData\Service\FileConverter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FileConverterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parserStrategy = $serviceLocator->get('HotelData\Parser\ParseStrategy');
        $inputDataFilter = $serviceLocator->get('HotelData\DataFilter');
        $console = $serviceLocator->get('console');
        $config = $serviceLocator->get('Config')['hotel-import'];
        $service = new FileConverter(
            $parserStrategy, 
            $inputDataFilter, 
            $console, 
            $config
        );
        
        return $service;
    }
}
