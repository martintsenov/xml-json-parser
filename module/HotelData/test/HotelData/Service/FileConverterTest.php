<?php

namespace HotelDataTest\Service;

use PHPUnit_Framework_TestCase;
use HotelData\Parser\ParseStrategy;
use HotelData\Service\FileConverter;

class FileConverterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers HotelData\Service\FileConverter::toCsv
     * 
     * @expectedException \HotelData\Exception\InvalidArgumentException
     */
    public function test_ToCsv_Throw_Exception()
    {
        $service = $this->getServiceInstance();
        $service->toCsv('wrong-file.xml');
    }
    
    /**
     * @covers HotelData\Service\FileConverter::toCsv
     * @dataProvider toCsvProvider
     */
    public function test_ToCsv_Success($file)
    {
        $service = $this->getServiceInstance();
        $this->assertTrue($service->toCsv($file));
    }
    
    public function toCsvProvider()
    {
        return [
            [
               'hotels-test.xml', 
            ],
            [
               'hotels-test.json', 
            ],
        ];
    }

        /**
     * 
     * @return FileConverter
     */
    private function getServiceInstance(): FileConverter
    {
        $inputDataFilterMock = $this->getMockBuilder('HotelData\Filter\DataFilter')
            ->disableOriginalConstructor()
            ->setMethods(['isValid', 'getValues'])
            ->getMock();
        $inputDataFilterMock->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));
        $inputDataFilterMock->expects($this->any())
            ->method('getValues')
            ->will($this->returnValue($this->getValidFilterValues()));
        
        $consoleMock = $this->getMockBuilder('Zend\Console\Adapter\AdapterInterface')
            ->disableOriginalConstructor()
            ->setMethods(['writeLine'])
            ->getMockForAbstractClass();
        $consoleMock->expects($this->any())
            ->method('writeLine')
            ->will($this->returnValue(true));
        
        $service = new FileConverter(
            new ParseStrategy, 
            $inputDataFilterMock, 
            $consoleMock, 
            [
                'folder' => __DIR__ . '/../data/' ,
            ]
        );
        
        return $service;
    }

    /**
     * 
     * @return array
     */
    private function getValidFilterValues(): array
    {
        return [
            'name' => 'Name 1',
            'address' => 'Address 1',
            'stars' => 1,
            'contact' => 'Contact name 1',
            'phone' => '1-111-1111',
            'uri' => 'http://localhost.com/1',
        ];
    }
}
