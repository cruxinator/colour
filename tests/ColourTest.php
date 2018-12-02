<?php
declare(strict_types=1);

use cruxinator\Colours\Colour;
use PHPUnit\Framework\TestCase;

class ColourTest extends TestCase
{
    public function testColourMix()
    {
        $expected = [
                     'ffffff' => array('ff0000','ff7f7f'), // ffffff + ff0000 = ff7f7f
                     '00ff00' => array('ff0000','7f7f00'),
                     '000000' => array('ff0000','7f0000'),
                     '002fff' => array('000000','00177f'),
                     '00ffed' => array('000000','007f76'),
                     'ff9a00' => array('000000','7f4d00'),
                     'ff9a00' => array('ffffff','ffcc7f'),
                     '00ff2d' => array('ffffff','7fff96'),
                     '8D43B4' => array('35CF64','61898c'),
                    ];
        foreach ($expected as $original => $complementary) {
            $colour = new Colour($original);
            $this->assertEquals($complementary[1], $colour->mix($complementary[0]), 'Incorrect mix colour returned.');
        }
    }

    public function testColourComplementary()
    {
        $expected = [
                     'ff0000' => '00ffff',
                     '0000ff' => 'ffff00',
                     '00ff00' => 'ff00ff',
                     'ffff00' => '0000ff',
                     '00ffff' => 'ff0000',
                     'ffff00' => '0000ff',

                     '49cbaf' => 'cb4965',
                     '003eb2' => 'b27400',
                     'b27400' => '003eb2',
                     'ffff99' => '9999ff',
                     'ccff00' => '3300ff',
                     '3300ff' => 'ccff00',
                     'fb4a2c' => '2cddfb',
                     '9cebff' => 'ffb09c',
                    ];

        foreach ($expected as $original => $complementary) {
            $colour = new Colour($original);
            $this->assertEquals($complementary, $colour->complementary(), 'Incorrect complementary colour returned.');
        }
    }


    public function testColourChangeDarken()
    {
        $expected = [
                     '336699' => '264d73',
                     '913399' => '6d2673'
                    ];

        foreach ($expected as $original => $darker) {
            $colour = new Colour($original);
            $this->assertEquals($darker, $colour->darken(), 'Incorrect darker colour returned.');
        }
    }

    public function testColourChangeLighten()
    {
        $expected = [
                     '336699' => '4080bf',
                     '913399' => 'b540bf'
                    ];

        foreach ($expected as $original => $lighter) {
            $colour = new Colour($original);
            $this->assertEquals($lighter, $colour->lighten(), 'Incorrect lighter colour returned.');
        }
    }

    public function testColourAnalyze()
    {
        $isDark = [
                   '000000' => true,
                   '336699' => true,
                   '913399' => true,
                   'E5C3E8' => false,
                   'D7E8DD' => false,
                   '218A47' => true,
                   '3D41CA' => true,
                   'E5CCDD' => false,
                   'FFFFFF' => false,
                  ];

        foreach ($isDark as $colourHex => $state) {
            $colour = new Colour($colourHex);
            $this->assertEquals($state, $colour->isDark(), 'Incorrect dark colour analyzed (#'. $colourHex .').');
        }

        $isLight = [
                    'FFFFFF' => true,
                    'A3FFE5' => true,
                    '000000' => false,
                   ];

        foreach ($isLight as $colourHex => $state) {
            $colour = new Colour($colourHex);
            $this->assertEquals($state, $colour->isLight(), 'Incorrect light colour analyzed (#'. $colourHex .').');
        }
    }
}
