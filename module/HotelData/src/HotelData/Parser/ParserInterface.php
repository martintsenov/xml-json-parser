<?php

namespace HotelData\Parser;

interface ParserInterface
{
    public function push(string $file);
    public function getResult(): array;
}
