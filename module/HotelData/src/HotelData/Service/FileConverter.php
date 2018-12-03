<?php

namespace HotelData\Service;

use HotelData\Exception\InvalidArgumentException;
use HotelData\Exception\RuntimeException;
use HotelData\Filter\DataFilter;
use HotelData\Parser\ParseStrategy;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;

class FileConverter
{
    /* @var $parserStrategy \HotelData\Parser\ParseStrategy */
    private $parserStrategy;
    /* @var $inputDataFilter \HotelData\Filter\DataFilter */
    private $inputDataFilter;
    /* @var $console \Zend\Console\Adapter\AdapterInterface */
    private $console;
    private $config;
    
    public function __construct(
        ParseStrategy $parserStrategy,
        DataFilter $inputDataFilter,
        ConsoleAdapterInterface $console,
        array $config
    ) {
        $this->parserStrategy = $parserStrategy;
        $this->inputDataFilter = $inputDataFilter;
        $this->console = $console;
        $this->config = $config;
    }

    /**
     * Parse and convert file content
     *
     * @param string $fileName
     * @return boolean
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function toCsv(string $fileName)
    {
        $file = $this->config['folder'] . $fileName;
        
        if (!is_file($file)) {
            throw new InvalidArgumentException('Incorrect file: ' . $file . '. Run \'php public\index.php\' for condole usage');
        }
        
        try {
            $this->console->writeLine('Start converting file ' . $file); 
            /* @var $parser \HotelData\Parser\ParserInterface */
            $parser = $this->parserStrategy->getParser($file);
            $parser->push($file);
            $validCounter = 1;
            $hotels = $parser->getResult(); 
            $csvFileName = $this->config['folder'] . 'valid-' . time() . '.csv';           
            $invalidData = [];
            $csvFile = fopen($csvFileName, 'w');
            fputcsv($csvFile, ['name', 'address', 'stars', 'contact', 'phone', 'uri']);
            
            foreach ($hotels as $hotelData) {
                $this->inputDataFilter->setData($hotelData);
                
                if (!$this->inputDataFilter->isValid()) {
                    $dataWithError = $this->inputDataFilter->getValues();
                    $dataWithError['error'] = $this->parseFilterError($this->inputDataFilter);
                    $invalidData[] = $dataWithError;
                    continue;
                }
                
                fputcsv($csvFile, $this->inputDataFilter->getValues());
                $validCounter++;
            }
            
            fclose($csvFile);
            chmod($csvFileName, 0777); 
            
            if (count($invalidData) > 0) {
                $csvErrorFileName = str_replace('valid-', 'error-data-', $csvFileName);
                $errorCounter = $this->createErrorDataFile($csvErrorFileName, $invalidData);
            }
            
            $this->console->writeLine($validCounter . ' lines converted'); 
            $this->console->writeLine('New csv file ' . $csvFileName); 
            if (count($invalidData) > 0) {
                $this->console->writeLine('There were invalid data, in total ' . $errorCounter . ' invalid lines'); 
                $this->console->writeLine('New csv file with errors to be fixed ' . $csvErrorFileName); 
            }                
            $this->console->writeLine('End'); 
        } catch (\Exception $ex) {
            throw new RuntimeException($ex->getMessage());
        }  

        return true;
    }
    
    /**
     * 
     * @param string $csvErrorFileName
     * @param array $invalidDataArr
     * @return int
     */
    private function createErrorDataFile(string $csvErrorFileName, array $invalidDataArr)
    {
        $errorCounter = 1;
        $csvFileError = fopen($csvErrorFileName, 'w');
        fputcsv($csvFileError, ['name', 'address', 'stars', 'contact', 'phone', 'uri', 'error']);

        foreach ($invalidDataArr as $invalidRow) {
            fputcsv($csvFileError, $invalidRow);
            $errorCounter++;
        }

        fclose($csvFileError);
        chmod($csvErrorFileName, 0777);
        
        return $errorCounter;
    }

    /**
     * Parse error to string
     *
     * @param DataFilter $filter
     * @return string
     */
    private function parseFilterError(DataFilter $filter): string
    {
        $errorMessage = [];

        foreach ($filter->getMessages() as $inputName => $errors) {
            foreach ($errors as $field => $message) {
                $errorMessage[] = $message;
            }
        }

        return implode(',', $errorMessage);
    }
}
