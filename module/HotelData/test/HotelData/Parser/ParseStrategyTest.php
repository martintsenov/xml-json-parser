<?php

namespace HotelDataTest\Parser;

use PHPUnit_Framework_TestCase;
use HotelData\Parser\ParseStrategy;

class ParseStrategyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers HotelData\Parser\ParseStrategy::getParser
     * @dataProvider getParserExceptionProvider
     * @expectedException \HotelData\Exception\InvalidArgumentException
     */
    public function test_GetParser_Throw_Exception($file)
    {
        $service = new ParseStrategy();
        $service->getParser($file);
    }
    
    public function getParserExceptionProvider()
    {
        return [
            [
               __DIR__ . '/../data/wrong-file.xml', 
            ],
            [
               __DIR__ . '/../data/hotels-test.txt', 
            ],
        ];
    }

    /**
     * @covers HotelData\Parser\ParseStrategy::getParser
     * @dataProvider getParserProvider
     */
    public function test_GetParser_Success($file, $instance)
    {
        $service = new ParseStrategy();
        $this->assertInstanceOf($instance, $service->getParser($file));
    }
    
    public function getParserProvider()
    {
        return [
            [
                __DIR__ . '/../data/hotels-test.xml',
                'HotelData\Parser\Xml',
            ],
            [
                __DIR__ . '/../data/hotels-test.json',
                'HotelData\Parser\Json'
            ],
        ];
    }
}
