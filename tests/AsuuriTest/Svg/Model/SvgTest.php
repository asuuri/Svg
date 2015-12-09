<?php

namespace AsuuriTest\Svg\Model;

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

        new Svg($validSvgFilePath);
    }

    public function testCanValidateParameters()
    {
        $validSvgFilePath = getcwd() . '/testData/valid/simple-parameters.svg';

        $svg = new Svg($validSvgFilePath);

        $this->assertTrue($svg->validate());
    }

    public function testValidatingParameterWithNoElementIdAttributeReturnsFalse()
    {
        $validSvgFilePath = getcwd() . '/testData/invalid/not-defined-elementId.svg';

        $svg = new Svg($validSvgFilePath);


        $this->assertFalse($svg->validate());

        $errors = $svg->getErrors(Svg::INCOMPLETE_DEFINITION_ERROR);
        $this->assertEquals(
            'elementId not set in parameter declaration, line 22',
            $errors[0]
        );
        $this->assertCount(1, $svg->getErrors());
    }

    public function testValidatingNonExistingElementIdReturnsFalse()
    {
        $validSvgFilePath = getcwd() . '/testData/invalid/non-existing-id.svg';

        $svg = new Svg($validSvgFilePath);

        $this->assertFalse($svg->validate());

        $errors = $svg->getErrors(Svg::MISSING_ELEMENT_ERROR);
        $this->assertEquals(
            'Could not find matching svg element with id \'main-text-one\'.',
            $errors[0]
        );
        $this->assertCount(1, $svg->getErrors());
    }

    public function testValidatingSvgWithSeveralNotUniqueIdsReturnsFalse()
    {
        $validSvgFilePath = getcwd() . '/testData/invalid/not-unique-id.svg';

        $svg = new Svg($validSvgFilePath);

        $this->assertFalse($svg->validate());
        $errors = $svg->getErrors(Svg::MULTIPLE_ID_DEFINITION_ERROR);
        $this->assertEquals(
            'Found several elements with the id \'main-text\'.',
            $errors[0]
        );
        $this->assertCount(1, $svg->getErrors());
    }

    public function testValidatingSvgUnknownParameterTypeReturnsFalse()
    {
        $validSvgFilePath = getcwd() . '/testData/invalid/unknown-parameter-type.svg';

        $svg = new Svg($validSvgFilePath);

        $this->assertFalse($svg->validate());
        $errors = $svg->getErrors(Svg::UNKNOWN_PARAMETER_TYPE_ERROR);
        $this->assertEquals(
            'Unknown parameter type for element \'use\'. Line 18',
            $errors[0]
        );
        $this->assertCount(1, $svg->getErrors());
    }

    public function testGetParametersReturnsAllDefinedParameters()
    {
        $validSvgFilePath = getcwd() . '/testData/valid/full-parameter-type-set.svg';

        $svg = new Svg($validSvgFilePath);

        $parameters = $svg->getParameters();

        $this->assertCount(3, $parameters);
    }

    public function testGetParameterIdReturnsCorrectValue()
    {
        $validSvgFilePath = getcwd() . '/testData/valid/full-parameter-type-set.svg';

        $svg = new Svg($validSvgFilePath);

        $parameters = $svg->getParameters();
        $parameter = $parameters[0];

        $this->assertEquals('element-text', $parameter->getId());
    }
}