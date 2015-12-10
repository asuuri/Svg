<?php

namespace AsuuriTest\Svg\Parameter;

use Asuuri\Svg\Parameter\Text;
use Asuuri\Svg\Model\Svg;
use Asuuri\Svg\Options\Render as RenderOptions;

class TextTest extends \PHPUnit_Framework_TestCase
{
    public function testSettingTextValue()
    {
        $validSvgFilePath = getcwd() . '/testData/valid/full-parameter-type-set.svg';
        $validRenderedFilePath = getcwd() . '/testData/valid/full-parameter-type-set-text-Foo.ppm';

        $svg = new Svg($validSvgFilePath);

        $parameters = $svg->getParameters();
        $textParam = $parameters[0];
        $textParam->setValue('Foo');

        $options = new RenderOptions();
        $options->setFormat(RenderOptions::FORMAT_PPM);
        $image = $svg->render($options);
        $tempFile = '/tmp/rendering_' . md5(rand(0, 999)) . '.ppm';

        file_put_contents($tempFile, $image);

        $this->assertFileEquals($validRenderedFilePath, $tempFile);

        unlink($tempFile);
    }
}