<?php

namespace HotelData\Controller\Factory;

use HotelData\Controller\ConsoleConvertController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConsoleConvertControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();
        $fileConverterService = $serviceLocator->get('HotelData\Service\FileConverter');
        
        return new ConsoleConvertController($fileConverterService);
    }
}
