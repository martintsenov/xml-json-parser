<?php

namespace HotelData\Parser;

use HotelData\Exception\InvalidArgumentException;
use HotelData\Parser\ParserInterface;
use HotelData\Parser\Xml as XmlParser;
use HotelData\Parser\Json as JsonParser;

class ParseStrategy
{
    const XML_FILE_TYPE = 'xml';
    const JSON_FILE_TYPE = 'json';

    private $parser = null;

    /**
     * Get parser based on the file type
     *
     * @param string $file
     * @return ParserInterface
     * @throws InvalidArgumentException
     */
    public function getParser($file): ParserInterface
    {
        if (!is_file($file)) {
            throw new InvalidArgumentException('Incorrect file: ' . $file);
        }
        
        $fileType = pathinfo($file, PATHINFO_EXTENSION);

        switch ($fileType) {
            case self::XML_FILE_TYPE:
                $this->parser = new XmlParser();
                break;
            case self::JSON_FILE_TYPE:
                $this->parser = new JsonParser();
                break;
        }
        
        if (is_null($this->parser)) {
            throw new InvalidArgumentException('Incorrect file type: ' . $fileType);
        }
        
        return $this->parser;
    }
}
