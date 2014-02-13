<?php

namespace AsuuriTest\Svg\Parameter;

use Asuuri\Svg\Parameter\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testHasElementReturnsTrueForAValidElement()
    {
        $factory = new Factory();

        $this->assertTrue($factory->hasElement('text'));
    }

    public function testHasElementReturnsFalseForAInvalidElement()
    {
        $factory = new Factory();

        $this->assertFalse($factory->hasElement('foo'));
    }

    /**
     * @dataProvider classElementMapDataProvider
     */
    public function testGetParameterReturnsRightInstance($elementId, $className)
    {
        $validSvgFilePath = getcwd() . '/testData/valid/full-parameter-type-set.svg';
        $element = $this->getElementFromSvgFile($elementId, $validSvgFilePath);

        $factory = new Factory();
        $this->assertInstanceOf($className, $factory->getParameter($element));
    }

    protected function getElementFromSvgFile($elementId, $svgFile)
    {
        $validSvgFilePath = getcwd() . '/testData/valid/full-parameter-type-set.svg';
        $doc = new \DOMDocument();
        $doc->load($validSvgFilePath);
        $xpath = new \DOMXPath($doc);

        $elements = $xpath->query("//*[@id='{$elementId}']");

        if ($elements->length === 1) {
            return $elements->item(0);
        }

        throw new \Exception(
            sprintf(
                'Could not find element \'%s\' from file \'%s\'!',
                $elementId,
                $svgFile
            )
        );
    }

    public function classElementMapDataProvider()
    {
        return array(
            array('element-text', '\Asuuri\Svg\Parameter\Text'),
            array('element-group', '\Asuuri\Svg\Parameter\Group'),
            array('element-rectangle', '\Asuuri\Svg\Parameter\Rectangle'),
        );
    }

    /**
     * @expectedException \Asuuri\Svg\Exception
     */
    public function testInvaligTagNameThrowsException()
    {
        $element = new \DOMElement('foo');

        $factory = new Factory();
        $factory->getParameter($element);
    }
}