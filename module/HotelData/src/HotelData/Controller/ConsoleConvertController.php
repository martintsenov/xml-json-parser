<?php

namespace HotelData\Controller;

use HotelData\Service\FileConverter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class ConsoleConvertController extends AbstractActionController
{
    /* @var $fileConverterService \HotelData\Service\FileConverter */
    private $fileConverterService;
    
    public function __construct(FileConverter $fileConverterService)
    {
        $this->fileConverterService = $fileConverterService;
    }

    public function indexAction()
    {
        return 'success'; // display standard index
    }
    
    public function convertAction()
    {
        try {
            $request = $this->getRequest();
            $fileName = $request->getParam('file', false);
            $this->fileConverterService->toCsv($fileName);
            
            return;
        } catch (\Exception $ex) {
            return 'error: '.$ex->getMessage();
        }
    }
}
