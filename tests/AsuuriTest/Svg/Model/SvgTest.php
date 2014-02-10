<?php

namespace AsuuriTest\Svg\Model\Svg;

use Asuuri\Svg\Model\Svg;

class SvgTest extends \PHPUnit_Framework_TestCase
{
    public function testCanLoadValidSvgFile()
    {
        $validSvgFilePath = getcwd() . '/testData/valid/simple.svg';

        $svg = new Svg($validSvgFilePath);

        $this->assertInstanceOf('\Asuuri\Svg\Model\Svg', $svg);
        $this->assertInstanceOf('\DOMDocument', $svg->getSvgDom());
    }

    /**
     * @expectedException \Asuuri\Svg\Model\FileException
     */
    public function testLoadingNonExistingSvgThrowsException()
    {
        $validSvgFilePath = getcwd() . '/non-existing.svg';

        $svg = new Svg($validSvgFilePath);
    }
}