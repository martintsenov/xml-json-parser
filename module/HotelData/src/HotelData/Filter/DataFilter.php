<?php

namespace HotelData\Filter;

use Zend\InputFilter\InputFilter;

class DataFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Callback',
                    'break_chain_on_failure' => true,
                    'options' => [
                        'callback' => function ($value) {
                            if (preg_match('/[^\x20-\x7f]/', $value)) {
                                return false;
                            }

                            return true;
                        },
                    ],  
                ],
            ],
        ]);
        $this->add([
            'name' => 'address',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
        ]);
        $this->add([
            'name' => 'stars',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Regex',
                    'break_chain_on_failure' => true,
                    'options' => [
                        'pattern' => '/[0-9]*/',
                    ],
                ]
            ],
        ]);
        $this->add([
            'name' => 'contact',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
        ]);
        $this->add([
            'name' => 'phone',
            'required' => true,
            'filters' => [
            ],
        ]);
        $this->add([
            'name' => 'uri',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Uri',
                    'break_chain_on_failure' => true,
                ],
            ],
        ]);
    }
}
