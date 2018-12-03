<?php

namespace HotelData;

use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements ConsoleUsageProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError'], 0);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'onRenderError'], 0);
    }

    public function onDispatchError($e)
    {
        return $this->getConsoleModelError($e);
    }

    public function onRenderError($e)
    {
        return $this->getConsoleModelError($e);
    }

    /**
     * Manages error's and return the error message
     *
     * @param MvcEvent $event
     * @return string
     */
    public function getConsoleModelError(MvcEvent $event)
    {
        $message = null;
        $error = $event->getError();

        if ($error) {
            $message = (string) $error;
        }

        $exception = $event->getParam('exception');

        if ($exception) {
            $message = $exception->getMessage();
        }

        if ($error == 'error-router-no-match') {
            $message = 'Resource not found. Use \'php public\index.php <route>\'';
        }

        if ($message) {
            $serviceLocator = $event->getApplication()->getServiceManager();
            /* @var $console \Zend\Console\Adapter\AdapterInterface */
            $console = $serviceLocator->get('console');
         
            $console->writeLine($message);  
        }

        return;        
    }

    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {
        return [
            'data convert --file=example.json'    => 'Convert input file to a CSV file',
            [ 'example.json | example.xml', 'file to be used' ],
        ];
    }
}
