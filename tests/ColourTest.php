<?php
declare(strict_types=1);

use cruxinator\Colours\Colour;
use PHPUnit\Framework\TestCase;

class ColourTest extends TestCase
{
    public function colourMixProvider()
    {
        return [
            ['ffffff', 'ff0000', 'ff7f7f'],
            ['00ff00', 'ff0000', '7f7f00'],
            ['000000', 'ff0000', '7f0000'],
            ['002fff', '000000', '00177f'],
            ['00ffed', '000000', '007f76'],
            ['ff9a00', '000000', '7f4d00'],
            ['ff9a00', 'ffffff', 'ffcc7f'],
            ['00ff2d', 'ffffff', '7fff96'],
            ['8D43B4', '35CF64', '61898c'],
        ];
    }

    /**
     * @dataProvider colourMixProvider
     */
    public function testColourMix($first, $second, $target)
    {
        $expected = new Colour($target);
        $expected = $expected->getHex();
        $colour = new Colour($first);
        $actual = $colour->mix($second);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider colourMixProvider
     */
    public function testColourMixReverse($first, $second, $target)
    {
        $expected = new Colour($target);
        $expected = $expected->getHex();
        $colour = new Colour($second);
        $actual = $colour->mix($first);
        $this->assertEquals($expected, $actual);
    }

    public function colourComplementProvider()
    {
        return [
            ['ff0000', '00ffff'],
            ['0000ff', 'ffff00'],
            ['00ff00', 'ff00ff'],
            ['ffff00', '0000ff'],
            ['00ffff', 'ff0000'],
            ['49cbaf', 'cb4965'],
            ['003eb2', 'b27400'],
            ['b27400', '003eb2'],
            ['ffff99', '9999ff'],
            ['ccff00', '3300ff'],
            ['3300ff', 'ccff00'],
            ['fb4a2c', '2cddfb'],
            ['9cebff', 'ffb09c']
        ];
    }

    /**
     * @dataProvider colourComplementProvider
     */
    public function testColourComplement($original, $complement)
    {
        $colour = new Colour($original);
        $colour = $colour->complementary();
        $this->assertEquals($complement, $colour);
    }

    /**
     * @dataProvider colourComplementProvider
     */
    public function testColourComplementReverse($original, $complement)
    {
        $colour = new Colour($complement);
        $colour = $colour->complementary();
        $this->assertEquals($original, $colour);
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
                    'FFFFFF' => true, 'A3FFE5' => true, '000000' => false,
                   ];

        foreach ($isLight as $colourHex => $state) {
            $colour = new Colour($colourHex);
            $this->assertEquals($state, $colour->isLight(), 'Incorrect light colour analyzed (#'. $colourHex .').');
        }
    }

    public function hexToHsvProvider()
    {
        return [
            ['000000', [0, 0, 0]],
            ['ffffff', [0, 0, 1]],
            ['ff0000', [0, 1, 1]],
            ['00ff00', [120, 1, 1]],
            ['0000ff', [240, 1, 1]],
            ['ffff00', [60, 1, 1]],
            ['00ffff', [180, 1, 1]],
            ['ff00ff', [300, 1, 1]],
            ['c0c0c0', [0, 0, 0.75]],
            ['808080', [0, 0, 0.5]],
            ['800000', [0, 1, 0.5]],
            ['808000', [60, 1, 0.5]],
            ['008000', [120, 1, 0.5]],
            ['800080', [300, 1, 0.5]],
            ['008080', [180, 1, 0.5]],
            ['000080', [240, 1, 0.5]],
            ['f0f8ff', ['208.0', '0.0588', '1.0000']],
            ['faebd7', ['34.3', '0.1400', '0.9804']],
            ['ffefdb', ['33.3', '0.1412', '1.0000']],
            ['eedfcc', ['33.5', '0.1429', '0.9333']],
            ['cdc0b0', ['33.1', '0.1415', '0.8039']],
            ['8b8378', ['34.7', '0.1367', '0.5451']],
            ['7fffd4', ['159.8', '0.5020', '1.0000']],
            ['76eec6', ['160.0', '0.5042', '0.9333']],
            ['458b74', ['160.3', '0.5036', '0.5451']],
            ['f0ffff', ['180.0', '0.0588', '1.0000']],
            ['e0eeee', ['180.0', '0.0588', '0.9333']],
            ['c1cdcd', ['180.0', '0.0585', '0.8039']],
            ['838b8b', ['180.0', '0.0576', '0.5451']],
            ['f5f5dc', ['60.0', '0.1020', '0.9608']],
            ['ffe4c4', ['32.5', '0.2314', '1.0000']],
            ['eed5b7', ['32.7', '0.2311', '0.9333']],
            ['cdb79e', ['31.9', '0.2293', '0.8039']],
            ['8b7d6b', ['33.8', '0.2302', '0.5451']],
            ['000000', ['0', '0', '0.0000']],
            ['ffebcd', ['36.0', '0.1961', '1.0000']],
            ['0000ff', ['240.0', '1.0000', '1.0000']],
            ['0000ee', ['240.0', '1.0000', '0.9333']],
            ['00008b', ['240.0', '1.0000', '0.5451']],
            ['8a2be2', ['271.1', '0.8097', '0.8863']],
            ['a52a2a', ['0.0', '0.7455', '0.6471']],
            ['ff4040', ['0.0', '0.7490', '1.0000']],
            ['ee3b3b', ['0.0', '0.7521', '0.9333']],
            ['cd3333', ['0.0', '0.7512', '0.8039']],
            ['8b2323', ['0.0', '0.7482', '0.5451']],
            ['deb887', ['33.8', '0.3919', '0.8706']],
            ['ffd39b', ['33.6', '0.3922', '1.0000']],
            ['eec591', ['33.5', '0.3908', '0.9333']],
            ['cdaa7d', ['33.7', '0.3902', '0.8039']],
            ['8b7355', ['33.3', '0.3885', '0.5451']],
            ['5f9ea0', ['181.8', '0.4063', '0.6275']],
            ['98f6ff', ['185.8', '0.4039', '1.0000']],
            ['8ee5ee', ['185.6', '0.4034', '0.9333']],
            ['7ac5cd', ['185.8', '0.4049', '0.8039']],
            ['53868b', ['185.4', '0.4029', '0.5451']],
            ['7fff00', ['90.1', '1.0000', '1.0000']],
            ['76ee00', ['90.3', '1.0000', '0.9333']],
            ['66cd00', ['90.1', '1.0000', '0.8039']],
            ['458b00', ['90.2', '1.0000', '0.5451']],
            ['d2691e', ['25.0', '0.8571', '0.8235']],
            ['ff7f24', ['24.9', '0.8588', '1.0000']],
            ['ee7621', ['24.9', '0.8613', '0.9333']],
            ['cd661d', ['24.9', '0.8585', '0.8039']],
            ['ff7f50', ['16.1', '0.6863', '1.0000']],
            ['ff7256', ['9.9', '0.6627', '1.0000']],
            ['ee6a50', ['9.9', '0.6639', '0.9333']],
            ['cd5b45', ['9.7', '0.6634', '0.8039']],
            ['8b3e2f', ['9.8', '0.6619', '0.5451']],
            ['6495ed', ['218.5', '0.5781', '0.9294']],
            ['fff8dc', ['48.0', '0.1373', '1.0000']],
            ['eee8cd', ['49.1', '0.1387', '0.9333']],
            ['cdc8b1', ['49.3', '0.1366', '0.8039']],
            ['8b8878', ['50.5', '0.1367', '0.5451']],
            ['00ffff', ['180.0', '1.0000', '1.0000']],
            ['00eeee', ['180.0', '1.0000', '0.9333']],
            ['00cdcd', ['180.0', '1.0000', '0.8039']],
            ['008b8b', ['180.0', '1.0000', '0.5451']],
            ['b8860b', ['42.7', '0.9402', '0.7216']],
            ['ffb90f', ['42.5', '0.9412', '1.0000']],
            ['eead0e', ['42.6', '0.9412', '0.9333']],
            ['cd950c', ['42.6', '0.9415', '0.8039']],
            ['8b6508', ['42.6', '0.9424', '0.5451']],
            ['006400', ['120.0', '1.0000', '0.3922']],
            ['bdb76b', ['55.6', '0.4339', '0.7412']],
            ['556b2f', ['82.0', '0.5607', '0.4196']],
            ['caff70', ['82.2', '0.5608', '1.0000']],
            ['bcee68', ['82.4', '0.5630', '0.9333']],
            ['a2cd5a', ['82.4', '0.5610', '0.8039']],
            ['6e8b3d', ['82.3', '0.5612', '0.5451']],
            ['ff8c00', ['32.9', '1.0000', '1.0000']],
            ['ff7f00', ['29.9', '1.0000', '1.0000']],
            ['ee7600', ['29.7', '1.0000', '0.9333']],
            ['cd6600', ['29.9', '1.0000', '0.8039']],
            ['8b4500', ['29.8', '1.0000', '0.5451']],
            ['9932cc', ['280.1', '0.7549', '0.8000']],
            ['bf3eff', ['280.1', '0.7569', '1.0000']],
            ['b23aee', ['280.0', '0.7563', '0.9333']],
            ['9a32cd', ['280.3', '0.7561', '0.8039']],
            ['68228b', ['280.0', '0.7554', '0.5451']],
            ['e9967a', ['15.1', '0.4764', '0.9137']],
            ['8fbc8f', ['120.0', '0.2394', '0.7373']],
            ['c1ffc1', ['120.0', '0.2431', '1.0000']],
            ['b4eeb4', ['120.0', '0.2437', '0.9333']],
            ['9bcd9b', ['120.0', '0.2439', '0.8039']],
            ['698b69', ['120.0', '0.2446', '0.5451']],
            ['483d8b', ['248.5', '0.5612', '0.5451']],
            ['2f4f4f', ['180.0', '0.4051', '0.3098']],
            ['97ffff', ['180.0', '0.4078', '1.0000']],
            ['8deeee', ['180.0', '0.4076', '0.9333']],
            ['79cdcd', ['180.0', '0.4098', '0.8039']],
            ['528b8b', ['180.0', '0.4101', '0.5451']],
            ['00ced1', ['180.9', '1.0000', '0.8196']],
            ['9400d3', ['282.1', '1.0000', '0.8275']],
            ['ff1493', ['327.6', '0.9216', '1.0000']],
            ['ee1289', ['327.5', '0.9244', '0.9333']],
            ['cd1076', ['327.6', '0.9220', '0.8039']],
            ['8b0a50', ['327.4', '0.9281', '0.5451']],
            ['00bfff', ['195.1', '1.0000', '1.0000']],
            ['00b2ee', ['195.1', '1.0000', '0.9333']],
            ['009acd', ['194.9', '1.0000', '0.8039']],
            ['00688b', ['195.1', '1.0000', '0.5451']],
            ['696969', ['0', '0', '0.4118']],
            ['1e90ff', ['209.6', '0.8824', '1.0000']],
            ['1c86ee', ['209.7', '0.8824', '0.9333']],
            ['1874cd', ['209.5', '0.8829', '0.8039']],
            ['104e8b', ['209.8', '0.8849', '0.5451']],
            ['b22222', ['0.0', '0.8090', '0.6980']],
            ['ff3030', ['0.0', '0.8118', '1.0000']],
            ['ee2c2c', ['0.0', '0.8151', '0.9333']],
            ['cd2626', ['0.0', '0.8146', '0.8039']],
            ['8b1a1a', ['0.0', '0.8129', '0.5451']],
            ['fffaf0', ['40.0', '0.0588', '1.0000']],
            ['228b22', ['120.0', '0.7554', '0.5451']],
            ['dcdcdc', ['0', '0', '0.8627']],
            ['f8f8ff', ['240.0', '0.0275', '1.0000']],
            ['ffd700', ['50.6', '1.0000', '1.0000']],
            ['eec900', ['50.7', '1.0000', '0.9333']],
            ['cdad00', ['50.6', '1.0000', '0.8039']],
            ['8b7500', ['50.5', '1.0000', '0.5451']],
            ['daa520', ['42.9', '0.8532', '0.8549']],
            ['ffc125', ['42.9', '0.8549', '1.0000']],
            ['eeb422', ['42.9', '0.8571', '0.9333']],
            ['cd9b1d', ['43.0', '0.8585', '0.8039']],
            ['8b6914', ['42.9', '0.8561', '0.5451']],
            ['bebebe', ['0', '0', '0.7451']],
            ['030303', ['0', '0', '0.0118']],
            ['1a1a1a', ['0', '0', '0.1020']],
            ['1c1c1c', ['0', '0', '0.1098']],
            ['1f1f1f', ['0', '0', '0.1216']],
            ['212121', ['0', '0', '0.1294']],
            ['242424', ['0', '0', '0.1412']],
            ['262626', ['0', '0', '0.1490']],
            ['292929', ['0', '0', '0.1608']],
            ['2b2b2b', ['0', '0', '0.1686']],
            ['2e2e2e', ['0', '0', '0.1804']],
            ['303030', ['0', '0', '0.1882']],
            ['050505', ['0', '0', '0.0196']],
            ['333333', ['0', '0', '0.2000']],
            ['363636', ['0', '0', '0.2118']],
            ['383838', ['0', '0', '0.2196']],
            ['3b3b3b', ['0', '0', '0.2314']],
            ['3d3d3d', ['0', '0', '0.2392']],
            ['404040', ['0', '0', '0.2510']],
            ['424242', ['0', '0', '0.2588']],
            ['454545', ['0', '0', '0.2706']],
            ['474747', ['0', '0', '0.2784']],
            ['4a4a4a', ['0', '0', '0.2902']],
            ['080808', ['0', '0', '0.0314']],
            ['4d4d4d', ['0', '0', '0.3020']],
            ['4f4f4f', ['0', '0', '0.3098']],
            ['525252', ['0', '0', '0.3216']],
            ['545454', ['0', '0', '0.3294']],
            ['575757', ['0', '0', '0.3412']],
            ['595959', ['0', '0', '0.3490']],
            ['5c5c5c', ['0', '0', '0.3608']],
            ['5e5e5e', ['0', '0', '0.3686']],
            ['616161', ['0', '0', '0.3804']],
            ['636363', ['0', '0', '0.3882']],
            ['0a0a0a', ['0', '0', '0.0392']],
            ['666666', ['0', '0', '0.4000']],
            ['696969', ['0', '0', '0.4118']],
            ['6b6b6b', ['0', '0', '0.4196']],
            ['6e6e6e', ['0', '0', '0.4314']],
            ['707070', ['0', '0', '0.4392']],
            ['737373', ['0', '0', '0.4510']],
            ['757575', ['0', '0', '0.4588']],
            ['787878', ['0', '0', '0.4706']],
            ['7a7a7a', ['0', '0', '0.4784']],
            ['7d7d7d', ['0', '0', '0.4902']],
            ['0d0d0d', ['0', '0', '0.0510']],
            ['7f7f7f', ['0', '0', '0.4980']],
            ['828282', ['0', '0', '0.5098']],
            ['858585', ['0', '0', '0.5216']],
            ['878787', ['0', '0', '0.5294']],
            ['8a8a8a', ['0', '0', '0.5412']],
            ['8c8c8c', ['0', '0', '0.5490']],
            ['8f8f8f', ['0', '0', '0.5608']],
            ['919191', ['0', '0', '0.5686']],
            ['949494', ['0', '0', '0.5804']],
            ['969696', ['0', '0', '0.5882']],
            ['0f0f0f', ['0', '0', '0.0588']],
            ['999999', ['0', '0', '0.6000']],
            ['9c9c9c', ['0', '0', '0.6118']],
            ['9e9e9e', ['0', '0', '0.6196']],
            ['a1a1a1', ['0', '0', '0.6314']],
            ['a3a3a3', ['0', '0', '0.6392']],
            ['a6a6a6', ['0', '0', '0.6510']],
            ['a8a8a8', ['0', '0', '0.6588']],
            ['ababab', ['0', '0', '0.6706']],
            ['adadad', ['0', '0', '0.6784']],
            ['b0b0b0', ['0', '0', '0.6902']],
            ['121212', ['0', '0', '0.0706']],
            ['b3b3b3', ['0', '0', '0.7020']],
            ['b5b5b5', ['0', '0', '0.7098']],
            ['b8b8b8', ['0', '0', '0.7216']],
            ['bababa', ['0', '0', '0.7294']],
            ['bdbdbd', ['0', '0', '0.7412']],
            ['bfbfbf', ['0', '0', '0.7490']],
            ['c2c2c2', ['0', '0', '0.7608']],
            ['c4c4c4', ['0', '0', '0.7686']],
            ['c7c7c7', ['0', '0', '0.7804']],
            ['c9c9c9', ['0', '0', '0.7882']],
            ['141414', ['0', '0', '0.0784']],
            ['cccccc', ['0', '0', '0.8000']],
            ['cfcfcf', ['0', '0', '0.8118']],
            ['d1d1d1', ['0', '0', '0.8196']],
            ['d4d4d4', ['0', '0', '0.8314']],
            ['d6d6d6', ['0', '0', '0.8392']],
            ['d9d9d9', ['0', '0', '0.8510']],
            ['dbdbdb', ['0', '0', '0.8588']],
            ['dedede', ['0', '0', '0.8706']],
            ['e0e0e0', ['0', '0', '0.8784']],
            ['e3e3e3', ['0', '0', '0.8902']],
            ['171717', ['0', '0', '0.0902']],
            ['e5e5e5', ['0', '0', '0.8980']],
            ['e8e8e8', ['0', '0', '0.9098']],
            ['ebebeb', ['0', '0', '0.9216']],
            ['ededed', ['0', '0', '0.9294']],
            ['f0f0f0', ['0', '0', '0.9412']],
            ['f2f2f2', ['0', '0', '0.9490']],
            ['f7f7f7', ['0', '0', '0.9686']],
            ['fafafa', ['0', '0', '0.9804']],
            ['fcfcfc', ['0', '0', '0.9882']],
            ['00ff00', ['120.0', '1.0000', '1.0000']],
            ['00ee00', ['120.0', '1.0000', '0.9333']],
            ['00cd00', ['120.0', '1.0000', '0.8039']],
            ['008b00', ['120.0', '1.0000', '0.5451']],
            ['adff2f', ['83.7', '0.8157', '1.0000']],
            ['f0fff0', ['120.0', '0.0588', '1.0000']],
            ['e0eee0', ['120.0', '0.0588', '0.9333']],
            ['c1cdc1', ['120.0', '0.0585', '0.8039']],
            ['838b83', ['120.0', '0.0576', '0.5451']],
            ['ff69b4', ['330.0', '0.5882', '1.0000']],
            ['ff6eb4', ['331.0', '0.5686', '1.0000']],
            ['ee6aa7', ['332.3', '0.5546', '0.9333']],
            ['cd6090', ['333.6', '0.5317', '0.8039']],
            ['8b3a62', ['330.4', '0.5827', '0.5451']],
            ['cd5c5c', ['0.0', '0.5512', '0.8039']],
            ['ff6a6a', ['0.0', '0.5843', '1.0000']],
            ['ee6363', ['0.0', '0.5840', '0.9333']],
            ['cd5555', ['0.0', '0.5854', '0.8039']],
            ['8b3a3a', ['0.0', '0.5827', '0.5451']],
            ['fffff0', ['60.0', '0.0588', '1.0000']],
            ['eeeee0', ['60.0', '0.0588', '0.9333']],
            ['cdcdc1', ['60.0', '0.0585', '0.8039']],
            ['8b8b83', ['60.0', '0.0576', '0.5451']],
            ['f0e68c', ['54.0', '0.4167', '0.9412']],
            ['fff78f', ['55.2', '0.4392', '1.0000']],
            ['eee685', ['55.4', '0.4412', '0.9333']],
            ['cdc673', ['55.3', '0.4390', '0.8039']],
            ['8b864e', ['55.1', '0.4388', '0.5451']],
            ['e6e6fa', ['240.0', '0.0800', '0.9804']],
            ['fff0f5', ['340.0', '0.0588', '1.0000']],
            ['eee0e5', ['338.6', '0.0588', '0.9333']],
            ['cdc1c5', ['340.0', '0.0585', '0.8039']],
            ['8b8386', ['337.5', '0.0576', '0.5451']],
            ['7cfc00', ['90.5', '1.0000', '0.9882']],
            ['fffacd', ['54.0', '0.1961', '1.0000']],
            ['eee9bf', ['53.6', '0.1975', '0.9333']],
            ['cdc9a5', ['54.0', '0.1951', '0.8039']],
            ['8b8970', ['55.6', '0.1942', '0.5451']],
            ['eedd82', ['50.6', '0.4538', '0.9333']],
            ['add8e6', ['194.7', '0.2478', '0.9020']],
            ['bfefff', ['195.0', '0.2510', '1.0000']],
            ['b2dfee', ['195.0', '0.2521', '0.9333']],
            ['9ac0cd', ['195.3', '0.2488', '0.8039']],
            ['68838b', ['193.7', '0.2518', '0.5451']],
            ['f08080', ['0.0', '0.4667', '0.9412']],
            ['e0ffff', ['180.0', '0.1216', '1.0000']],
            ['d1eeee', ['180.0', '0.1218', '0.9333']],
            ['b4cdcd', ['180.0', '0.1220', '0.8039']],
            ['7a8b8b', ['180.0', '0.1223', '0.5451']],
            ['ffec8b', ['50.2', '0.4549', '1.0000']],
            ['eedc82', ['50.0', '0.4538', '0.9333']],
            ['cdbe70', ['50.3', '0.4537', '0.8039']],
            ['8b814c', ['50.5', '0.4532', '0.5451']],
            ['fafad2', ['60.0', '0.1600', '0.9804']],
            ['d3d3d3', ['0', '0', '0.8275']],
            ['ffb6c1', ['351.0', '0.2863', '1.0000']],
            ['ffaeb9', ['351.9', '0.3176', '1.0000']],
            ['eea2ad', ['351.3', '0.3193', '0.9333']],
            ['cd8c95', ['351.7', '0.3171', '0.8039']],
            ['8b5f65', ['351.8', '0.3165', '0.5451']],
            ['ffa07a', ['17.1', '0.5216', '1.0000']],
            ['ee9572', ['16.9', '0.5210', '0.9333']],
            ['cd8162', ['17.4', '0.5220', '0.8039']],
            ['8b5742', ['17.3', '0.5252', '0.5451']],
            ['20b2aa', ['176.7', '0.8202', '0.6980']],
            ['87cefa', ['203.0', '0.4600', '0.9804']],
            ['b0e2ff', ['202.0', '0.3098', '1.0000']],
            ['a4d3ee', ['201.9', '0.3109', '0.9333']],
            ['8db6cd', ['201.6', '0.3122', '0.8039']],
            ['607b8b', ['202.3', '0.3094', '0.5451']],
            ['8470ff', ['248.4', '0.5608', '1.0000']],
            ['778899', ['210.0', '0.2222', '0.6000']],
            ['b0c4de', ['213.9', '0.2072', '0.8706']],
            ['cae1ff', ['214.0', '0.2078', '1.0000']],
            ['bcd2ee', ['213.6', '0.2101', '0.9333']],
            ['a2b5cd', ['213.5', '0.2098', '0.8039']],
            ['6e7b8b', ['213.1', '0.2086', '0.5451']],
            ['ffffe0', ['60.0', '0.1216', '1.0000']],
            ['eeeed1', ['60.0', '0.1218', '0.9333']],
            ['cdcdb4', ['60.0', '0.1220', '0.8039']],
            ['8b8b7a', ['60.0', '0.1223', '0.5451']],
            ['32cd32', ['120.0', '0.7561', '0.8039']],
            ['faf0e6', ['30.0', '0.0800', '0.9804']],
            ['ff00ff', ['300.0', '1.0000', '1.0000']],
            ['ee00ee', ['300.0', '1.0000', '0.9333']],
            ['cd00cd', ['300.0', '1.0000', '0.8039']],
            ['8b008b', ['300.0', '1.0000', '0.5451']],
            ['b03060', ['337.5', '0.7273', '0.6902']],
            ['ff34b3', ['322.5', '0.7961', '1.0000']],
            ['ee30a7', ['322.4', '0.7983', '0.9333']],
            ['cd2990', ['322.3', '0.8000', '0.8039']],
            ['8b1c62', ['322.2', '0.7986', '0.5451']],
            ['66cdaa', ['159.6', '0.5024', '0.8039']],
            ['66cdaa', ['159.6', '0.5024', '0.8039']],
            ['0000cd', ['240.0', '1.0000', '0.8039']],
            ['ba55d3', ['288.1', '0.5972', '0.8275']],
            ['e066ff', ['287.8', '0.6000', '1.0000']],
            ['d15fee', ['287.8', '0.6008', '0.9333']],
            ['b452cd', ['287.8', '0.6000', '0.8039']],
            ['7a378b', ['287.9', '0.6043', '0.5451']],
            ['9370db', ['259.6', '0.4886', '0.8588']],
            ['ab82ff', ['259.7', '0.4902', '1.0000']],
            ['9f79ee', ['259.5', '0.4916', '0.9333']],
            ['8968cd', ['259.6', '0.4927', '0.8039']],
            ['5d478b', ['259.4', '0.4892', '0.5451']],
            ['3cb371', ['146.7', '0.6648', '0.7020']],
            ['7b68ee', ['248.5', '0.5630', '0.9333']],
            ['00fa9a', ['157.0', '1.0000', '0.9804']],
            ['48d1cc', ['177.8', '0.6555', '0.8196']],
            ['c71585', ['322.2', '0.8945', '0.7804']],
            ['191970', ['240.0', '0.7768', '0.4392']],
            ['f5fffa', ['150.0', '0.0392', '1.0000']],
            ['ffe4e1', ['6.0', '0.1176', '1.0000']],
            ['eed5d2', ['6.4', '0.1176', '0.9333']],
            ['cdb7b5', ['5.0', '0.1171', '0.8039']],
            ['8b7d7b', ['7.5', '0.1151', '0.5451']],
            ['ffe4b5', ['38.1', '0.2902', '1.0000']],
            ['ffdead', ['35.9', '0.3216', '1.0000']],
            ['eecfa1', ['35.8', '0.3235', '0.9333']],
            ['cdb38b', ['36.4', '0.3220', '0.8039']],
            ['8b795e', ['36.0', '0.3237', '0.5451']],
            ['000080', ['240.0', '1.0000', '0.5020']],
            ['fef5e6', ['39.1', '0.0909', '0.9922']],
            ['6b8e23', ['79.6', '0.7535', '0.5569']],
            ['c0ff3e', ['79.6', '0.7569', '1.0000']],
            ['b3ee3a', ['79.7', '0.7563', '0.9333']],
            ['698b22', ['79.4', '0.7554', '0.5451']],
            ['ffa500', ['38.8', '1.0000', '1.0000']],
            ['ee9a00', ['38.8', '1.0000', '0.9333']],
            ['cd8500', ['38.9', '1.0000', '0.8039']],
            ['8b5a00', ['38.8', '1.0000', '0.5451']],
            ['ff4500', ['16.2', '1.0000', '1.0000']],
            ['ee4000', ['16.1', '1.0000', '0.9333']],
            ['cd3700', ['16.1', '1.0000', '0.8039']],
            ['8b2500', ['16.0', '1.0000', '0.5451']],
            ['da70d6', ['302.3', '0.4862', '0.8549']],
            ['ff83fb', ['302.4', '0.4863', '1.0000']],
            ['ee7ae9', ['302.6', '0.4874', '0.9333']],
            ['cd69c9', ['302.4', '0.4878', '0.8039']],
            ['8b4789', ['301.8', '0.4892', '0.5451']],
            ['db7093', ['340.4', '0.4886', '0.8588']],
            ['eee8aa', ['54.7', '0.2857', '0.9333']],
            ['98fb98', ['120.0', '0.3944', '0.9843']],
            ['9aff9a', ['120.0', '0.3961', '1.0000']],
            ['90ee90', ['120.0', '0.3950', '0.9333']],
            ['7ccd7c', ['120.0', '0.3951', '0.8039']],
            ['548b54', ['120.0', '0.3957', '0.5451']],
            ['afeeee', ['180.0', '0.2647', '0.9333']],
            ['bbffff', ['180.0', '0.2667', '1.0000']],
            ['aeeeee', ['180.0', '0.2689', '0.9333']],
            ['96cdcd', ['180.0', '0.2683', '0.8039']],
            ['668b8b', ['180.0', '0.2662', '0.5451']],
            ['db7093', ['340.4', '0.4886', '0.8588']],
            ['ff82ab', ['340.3', '0.4902', '1.0000']],
            ['ee799f', ['340.5', '0.4916', '0.9333']],
            ['cd6889', ['340.4', '0.4927', '0.8039']],
            ['8b475d', ['340.6', '0.4892', '0.5451']],
            ['ffefd5', ['37.1', '0.1647', '1.0000']],
            ['ffdab9', ['28.3', '0.2745', '1.0000']],
            ['eecbad', ['27.7', '0.2731', '0.9333']],
            ['cdaf95', ['27.9', '0.2732', '0.8039']],
            ['8b7765', ['28.4', '0.2734', '0.5451']],
            ['ffc0cb', ['349.5', '0.2471', '1.0000']],
            ['ffb5c5', ['347.0', '0.2902', '1.0000']],
            ['eea9b8', ['347.0', '0.2899', '0.9333']],
            ['cd919e', ['347.0', '0.2927', '0.8039']],
            ['8b636c', ['346.5', '0.2878', '0.5451']],
            ['dda0dd', ['300.0', '0.2760', '0.8667']],
            ['ffbbff', ['300.0', '0.2667', '1.0000']],
            ['eeaeee', ['300.0', '0.2689', '0.9333']],
            ['cd96cd', ['300.0', '0.2683', '0.8039']],
            ['8b668b', ['300.0', '0.2662', '0.5451']],
            ['b0e0e6', ['186.7', '0.2348', '0.9020']],
            ['a020f0', ['276.9', '0.8667', '0.9412']],
            ['9b30ff', ['271.0', '0.8118', '1.0000']],
            ['912cee', ['271.2', '0.8151', '0.9333']],
            ['7d26cd', ['271.3', '0.8146', '0.8039']],
            ['551a8b', ['271.3', '0.8129', '0.5451']],
            ['ff0000', ['0.0', '1.0000', '1.0000']],
            ['ee0000', ['0.0', '1.0000', '0.9333']],
            ['cd0000', ['0.0', '1.0000', '0.8039']],
            ['8b0000', ['0.0', '1.0000', '0.5451']],
            ['bc8f8f', ['0.0', '0.2394', '0.7373']],
            ['ffc1c1', ['0.0', '0.2431', '1.0000']],
            ['eeb4b4', ['0.0', '0.2437', '0.9333']],
            ['cd9b9b', ['0.0', '0.2439', '0.8039']],
            ['8b6969', ['0.0', '0.2446', '0.5451']],
            ['4169e1', ['225.0', '0.7111', '0.8824']],
            ['4876ff', ['224.9', '0.7176', '1.0000']],
            ['436eee', ['224.9', '0.7185', '0.9333']],
            ['3a5fcd', ['224.9', '0.7171', '0.8039']],
            ['27408b', ['225.0', '0.7194', '0.5451']],
            ['8b4513', ['25.0', '0.8633', '0.5451']],
            ['fa8072', ['6.2', '0.5440', '0.9804']],
            ['ff8c69', ['14.0', '0.5882', '1.0000']],
            ['ee8262', ['13.7', '0.5882', '0.9333']],
            ['cd7054', ['13.9', '0.5902', '0.8039']],
            ['8b4c39', ['13.9', '0.5899', '0.5451']],
            ['f4a460', ['27.6', '0.6066', '0.9569']],
            ['54ff9f', ['146.3', '0.6706', '1.0000']],
            ['4eee94', ['146.3', '0.6723', '0.9333']],
            ['43cd80', ['146.5', '0.6732', '0.8039']],
            ['2e8b57', ['146.5', '0.6691', '0.5451']],
            ['fff5ee', ['24.7', '0.0667', '1.0000']],
            ['eee5de', ['26.3', '0.0672', '0.9333']],
            ['cdc5bf', ['25.7', '0.0683', '0.8039']],
            ['8b8682', ['26.7', '0.0647', '0.5451']],
            ['a0522d', ['19.3', '0.7187', '0.6275']],
            ['ff8247', ['19.2', '0.7216', '1.0000']],
            ['ee7942', ['19.2', '0.7227', '0.9333']],
            ['cd6839', ['19.1', '0.7220', '0.8039']],
            ['8b4726', ['19.6', '0.7266', '0.5451']],
            ['87ceeb', ['197.4', '0.4255', '0.9216']],
            ['87ceff', ['204.5', '0.4706', '1.0000']],
            ['7ec0ee', ['204.6', '0.4706', '0.9333']],
            ['6ca6cd', ['204.1', '0.4732', '0.8039']],
            ['4a708b', ['204.9', '0.4676', '0.5451']],
            ['6a5acd', ['248.3', '0.5610', '0.8039']],
            ['836fff', ['248.3', '0.5647', '1.0000']],
            ['7a67ee', ['248.4', '0.5672', '0.9333']],
            ['6959cd', ['248.3', '0.5659', '0.8039']],
            ['473c8b', ['248.4', '0.5683', '0.5451']],
            ['708090', ['210.0', '0.2222', '0.5647']],
            ['c6e2ff', ['210.5', '0.2235', '1.0000']],
            ['b9d3ee', ['210.6', '0.2227', '0.9333']],
            ['9fb6cd', ['210.0', '0.2244', '0.8039']],
            ['6c7b8b', ['211.0', '0.2230', '0.5451']],
            ['fffafa', ['0.0', '0.0196', '1.0000']],
            ['eee9e9', ['0.0', '0.0210', '0.9333']],
            ['cdc9c9', ['0.0', '0.0195', '0.8039']],
            ['8b8989', ['0.0', '0.0144', '0.5451']],
            ['00ff7f', ['149.9', '1.0000', '1.0000']],
            ['00ee76', ['149.7', '1.0000', '0.9333']],
            ['00cd66', ['149.9', '1.0000', '0.8039']],
            ['008b45', ['149.8', '1.0000', '0.5451']],
            ['4682b4', ['207.3', '0.6111', '0.7059']],
            ['63b8ff', ['207.3', '0.6118', '1.0000']],
            ['5cacee', ['207.1', '0.6134', '0.9333']],
            ['4f94cd', ['207.1', '0.6146', '0.8039']],
            ['36648b', ['207.5', '0.6115', '0.5451']],
            ['d2b48c', ['34.3', '0.3333', '0.8235']],
            ['ffa54f', ['29.3', '0.6902', '1.0000']],
            ['ee9a49', ['29.5', '0.6933', '0.9333']],
            ['cd853f', ['29.6', '0.6927', '0.8039']],
            ['8b5a2b', ['29.4', '0.6906', '0.5451']],
            ['d8bfd8', ['300.0', '0.1157', '0.8471']],
            ['ffe1ff', ['300.0', '0.1176', '1.0000']],
            ['eed2ee', ['300.0', '0.1176', '0.9333']],
            ['cdb5cd', ['300.0', '0.1171', '0.8039']],
            ['8b7b8b', ['300.0', '0.1151', '0.5451']],
            ['ff6347', ['9.1', '0.7216', '1.0000']],
            ['ee5c42', ['9.1', '0.7227', '0.9333']],
            ['cd4f39', ['8.9', '0.7220', '0.8039']],
            ['8b3626', ['9.5', '0.7266', '0.5451']],
            ['40e0d0', ['174.0', '0.7143', '0.8784']],
            ['00f5ff', ['182.4', '1.0000', '1.0000']],
            ['00e5ee', ['182.3', '1.0000', '0.9333']],
            ['00c5cd', ['182.3', '1.0000', '0.8039']],
            ['00868b', ['182.2', '1.0000', '0.5451']],
            ['ee82ee', ['300.0', '0.4538', '0.9333']],
            ['d02090', ['321.8', '0.8462', '0.8157']],
            ['ff3e96', ['332.6', '0.7569', '1.0000']],
            ['ee3a8c', ['332.7', '0.7563', '0.9333']],
            ['cd3278', ['332.9', '0.7561', '0.8039']],
            ['8b2252', ['332.6', '0.7554', '0.5451']],
            ['f5deb3', ['39.1', '0.2694', '0.9608']],
            ['ffe7ba', ['39.1', '0.2706', '1.0000']],
            ['eed8ae', ['39.4', '0.2689', '0.9333']],
            ['cdba96', ['39.3', '0.2683', '0.8039']],
            ['8b7e66', ['38.9', '0.2662', '0.5451']],
            ['ffffff', ['0', '0', '1.0000']],
            ['f5f5f5', ['0', '0', '0.9608']],
            ['ffff00', ['60.0', '1.0000', '1.0000']],
            ['eeee00', ['60.0', '1.0000', '0.9333']],
            ['cdcd00', ['60.0', '1.0000', '0.8039']],
            ['8b8b00', ['60.0', '1.0000', '0.5451']],
            ['9acd32', ['79.7', '0.7561', '0.8039']],
        ];
    }

    /**
     * @dataProvider hexToHsvProvider
     */
    public function testHsvToHex($hex, $hsv)
    {
        $actual = Colour::hsvToHex($hsv);
        $this->assertEquals($hex, $actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testHsvToHexInputLengthTooShort()
    {
        $hsv = [];
        $actual = Colour::hsvToHex($hsv);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testHsvToHexInputLengthTooLong()
    {
        $hsv = [180, 1, 1, 1];
        $actual = Colour::hsvToHex($hsv);
    }

    public function badHsvProvider()
    {
        return [
            [[-0.01, -0.5, -0.5]],
            [[360.0, 1.01, 1.01]],
            [[-0.01, 0.5, 0.5]],
            [[0.01, -0.5, 0.5]],
            [[0.01, 0.5, -0.5]],
            [[360.0, 0.5, 0.5]],
            [[180.0, 1.01, 0.5]],
            [[180.0, 0.5, 1.01]],
        ];
    }

    /**
     * @dataProvider badHsvProvider
     * @expectedException \InvalidArgumentException
     */
    public function testHsvBadInput($hsv)
    {
        $actual = Colour::hsvToHex($hsv);
    }
}
