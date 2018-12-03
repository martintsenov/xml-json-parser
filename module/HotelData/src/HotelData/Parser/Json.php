<?php

namespace HotelData\Parser;

use HotelData\Parser\ParserInterface;
use HotelData\Exception\InvalidArgumentException;

/**
 * Usage
 *
 * use HotelData\Parser\Json as JsonParser;
 * $parser = new JsonParser();
 * $parser->push($file);
 * $hotels = $parser->getResult();
 * foreach ($hotels as $hotelData) {
 *    //loop over hotel data
 * }
 */
class Json implements ParserInterface
{
    private $buffer = '';
    private $endCharacter = null;
    private $assoc = true;
    private $hotels = [];  

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
        
        $chunk = file_get_contents($file);
        $objects = [];
        
        while ($chunk !== '') {
            if ($this->endCharacter === null) {
                // trim leading whitespace
                $chunk = ltrim($chunk);
                
                if ($chunk === '},') {
                    break;
                } elseif ($chunk[0] === '[') {
                    // array/list delimiter
                    $this->endCharacter = ']';
                } elseif ($chunk[0] === '{') {
                    // object/hash delimiter
                    $this->endCharacter = '}';
                } else {
                    continue;
                }
            }
            
            $pos = strpos($chunk, $this->endCharacter);
            
            if ($pos === false) { // no end found in chunk
                $this->buffer .= $chunk;
                break;
            }
            
            // possible end found in chunk, select from buffer, keep remaining chunk
            $this->buffer .= substr($chunk, 0, $pos + 1);
            $chunk = substr($chunk, $pos + 1);
            // try to parse
            $json = json_decode($this->buffer, $this->assoc);

            if ($json !== null) {
                $objects[] = $json;
                // clear parsed buffer and continue
                $this->buffer = '';
                $this->endCharacter = null;
            }
        }
        
        $this->hotels = $objects[0];
    }

    /**
     * Check class doc-block
     *
     * @return array
     */
    public function getResult(): array
    {
        return $this->hotels;
    }
}
