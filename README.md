XML/JSON data parser
====================

Installation
------------
1. Download composer from https://getcomposer.org/download/ (composer.phar) 
   and then run `php composer.phar install`
2. Prototype is build over Zend Framework and PHP.
3. Set Http server DocumentRoot to `<htdocs-folder-path>/xml-json-parser/public`
4. You can change the folder to be used for import/export in `configuration.php`. 
   By default it is set to `/data`. Files that will be exported to CSV have to 
   be placed in this folder 
5. Check app usages in `Description` section
6. Check requirements in `Requirements`, note that all thir-party libraries and 
   frameworks will be gathered by `php composer.phar install` setup

Description
-----------
* Copy file(s) to be converted in `/data`
* Import data
  Go to `<htdocs-folder-path>/xml-json-parser` folder and execute 
  `php public\index.php data convert --file=hotels-sample.xml` to import messages. 
  With `php public\index.php` we can check all possible console commands
* Import will create file with valid input data like `valid-1531130738.csv` 
  (`valid-<timestamp>.csv`) and if there is validation error will create also
  `error-data-1531130738.csv` file
* Sample cvs and json files are provided for test in `/data`
* Unit Test
  Run test with `php vendor\phpunit\phpunit\phpunit --configuration tests/phpunit.xml --debug --stop-on-fail`

Requirements
------------
* [Zend Framework 2](https://github.com/zendframework/zf2) (2.4.*)
* [PHP](https://secure.php.net/downloads.php) (7.0.*)
