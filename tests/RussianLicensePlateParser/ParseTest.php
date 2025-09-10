<?php

namespace Tests\RussianLicensePlateParser;

use LeTraceurSnork\RussianLicensePlates\RussianLicensePlateParser;
use PHPUnit\Framework\TestCase;

class ParseTest extends TestCase
{
    public function testParsePassengerPlate()
    {
        $input = 'А123ВС77';
        $expected = [
            'type'         => RussianLicensePlateParser::GROUP_1_TYPE_1,
            'first_letter' => 'А',
            'digits'       => '123',
            'last_letters' => 'ВС',
            'region'       => '77',
        ];

        $parser = new RussianLicensePlateParser();
        $result = $parser->parse($input);

        $this->assertEquals($expected, $result);
    }
}
