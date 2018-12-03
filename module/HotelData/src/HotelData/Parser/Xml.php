<?php

namespace HotelData\Parser;

use HotelData\Exception\InvalidArgumentException;
use HotelData\Parser\ParserInterface;

/**
 * Usage
 *
 * use HotelData\Parser\Xml as XmlParser;
 * $parser = new XmlParser();
 * $parser->push($file);
 * $hotels = $parser->getResult();
 * foreach ($hotels as $hotelData) {
 *    //loop over hotel data
 * }
 */
class Xml implements ParserInterface
{
    private $hotels = [];
    private $elements = null;
    private $elementArr = ['NAME', 'ADDRESS', 'STARS', 'CONTACT', 'PHONE', 'URI'];
    private $rootElement = 'HOTEL';
    private $parser;

    public function __construct()
    {
        $parser = xml_parser_create();
        xml_set_element_handler($parser, [$this, 'startElements'], [$this, 'endElements']);
        xml_set_character_data_handler($parser, [$this, 'characterData']);
        $this->parser = $parser;
    }

    /**
     * Check class doc-block
     *
     * @param string $file
     * @throws InvalidArgumentException
     */
    public function push(string $file)
    {
        if (!($handle = fopen($file, 'r'))) {
            throw new InvalidArgumentException('Could not open the input: ' . $file);
        }

        while ($data = fread($handle, 4096)) {
            $this->start($data);
        }

        $this->end();
    }

    /**
     * Check class doc-block
     * 
     * @return type
     */
    public function getResult(): array
    {
        return $this->hotels;
    }
    
    /**
     * @param type $data
     */
    private function start($data)
    {
        xml_parse($this->parser, $data);
    }

    private function end()
    {
        xml_parser_free($this->parser);
    }    

    /**
     * 
     * @param type $parser
     * @param type $name
     * @param type $attrs
     */
    private function startElements($parser, $name, $attrs)
    {
        if (!empty($name)) {
            if ($name == $this->rootElement) {
                // creating an array to store information
                $this->hotels[] = [];
            }
            $this->elements = $name;
        }
    }

    /**
     * 
     * @param type $parser
     * @param type $name
     */
    private function endElements($parser, $name)
    {
        if (!empty($name)) {
            $this->elements = null;
        }
    }

    /**
     * 
     * @param type $parser
     * @param type $data
     */
    private function characterData($parser, $data)
    {
        if (!empty($data)) {
            if (in_array($this->elements, $this->elementArr)) {
                $this->hotels[count($this->hotels) - 1][strtolower($this->elements)] = trim($data);
            }
        }
    }

}
