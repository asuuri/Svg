<?php

namespace Asuuri\Svg\Model;

use Asuuri\Svg\Parameter\Factory as ParameterFactory;

class Svg {

    const VALIDATION_ERROR = 'Validation error';

    const INCOMPLETE_DEFINITION_ERROR = 'Incomplete definition error';

    const UNKNOWN_PARAMETER_TYPE_ERROR = 'Unknown parameter type error';

    const MULTIPLE_ID_DEFINITION_ERROR = 'Multiple id definition error';

    const MISSING_ELEMENT_ERROR = 'Missing element error';

    /**
     * @var \DOMDocument
     */
    protected $svgDom = null;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var \Asuuri\Svg\Parameter\Factory
     */
    protected $parameterFactory = null;

    /**
     * @var string
     */
    protected $parametersXPath = '//asuuri:parameters/asuuri:parameter';

    /**
     * @param string $svgFile
     */
    public function __construct($svgFile = null)
    {
        if ($svgFile) {
            $this->load($svgFile);
        }
    }

    /**
     * @return \Asuuri\Svg\Parameter\Factory
     */
    public function getParameterFactory() {
        if ($this->parameterFactory === null) {
            $this->parameterFactory = new ParameterFactory();
        }

        return $this->parameterFactory;
    }

    /**
     * @param string $svgFile
     * @throws FileException
     * @return \Asuuri\Svg\Model\Svg
     */
    public function load($svgFile)
    {
        if (is_file($svgFile)) {
            return $this->loadSVG($svgFile);
        } else {
            throw new FileException(
                sprintf('Could not find svg file "%s".', (string) $svgFile)
            );
        }
    }

    /**
     * @param string $svgFile
     * @return \Asuuri\Svg\Model\Svg
     */
    protected function loadSVG($svgFile)
    {
        $dom = new \DOMDocument();
        $dom->validateOnParse = false;
        $dom->load($svgFile);
        $this->setSvgDom($dom);

        return $this;
    }

    /**
     * @return \DOMDocument
     */
    public function getSvgDom() {
        return $this->svgDom;
    }

    /**
     *
     * @param \DOMDocument $svgDom
     * @return \Asuuri\Svg\Model\Svg
     */
    public function setSvgDom(\DOMDocument $svgDom) {
        $this->svgDom = $svgDom;

        return $this;
    }

    /**
     * @return \DOMXpath
     */
    protected function getXPath()
    {
        $dom = $this->getSvgDom();
        $xpath = new \DOMXpath($dom);
        $xpath->registerNamespace('asuuri', 'http://asuuri.net/2014/svg10');

        return $xpath;
    }

    public function validate()
    {
        $xpath = $this->getXPath();

        $result = true;

        if (false === $this->validateParameters($xpath)) {
            $result = false;
        }

        return $result;
    }

    private function validateParameters(\DOMXPath $xpath)
    {
        $result = true;
        $parameters = $xpath->query($this->parametersXPath);
        foreach($parameters as $parameter) {
            $id = $parameter->getAttribute('elementId');

            if ('' === $id) {
                $msg = 'elementId not set in parameter declaration, line %d';
                $this->addError(
                    self::INCOMPLETE_DEFINITION_ERROR,
                    sprintf($msg, $parameter->getLineNo())
                );

                return false;
            }

            $elements = $xpath->query(sprintf('//*[@id=\'%s\']', $id));
            $element = false;
            if ($elements->length === 1) {
                $element = $elements->item(0);
            } else if ($elements->length > 1) {
                $msg = 'Found several elements with the id \'%s\'.';
                $this->addError(
                    self::MULTIPLE_ID_DEFINITION_ERROR,
                    sprintf($msg, $id)
                );
                $result = false;
            } else if ($elements->length < 1) {
                $msg = 'Could not find matching svg element with id \'%s\'.';
                $this->addError(
                    self::MISSING_ELEMENT_ERROR,
                    sprintf($msg, $id)
                );
                $result = false;
            }

            if ($element && false === $this->validateParameter($element)) {
                $result = false;
            }
        }

        return $result;
    }

    private function validateParameter(\DOMElement $svgElement)
    {
        $result = true;
        $tagName = $svgElement->tagName;
        $parameterFactory = $this->getParameterFactory();
        if (false === $parameterFactory->hasElement($tagName)) {
            $msg = 'Unknown parameter type for element \'%s\'. Line %d';
            $this->addError(
                self::UNKNOWN_PARAMETER_TYPE_ERROR,
                sprintf($msg, $tagName, $svgElement->getLineNo())
            );

            $result = false;
        }

        return $result;
    }

    /**
     * @param string $error Error type
     * @param string $message Message for the error
     */
    public function addError($error, $message)
    {
        if (false === isset($this->errors[$error])) {
            $this->errors[$error] = array();
        }

        $this->errors[$error][] = $message;
    }

    /**
     * @param string $errorType
     * @return array
     */
    public function getErrors($errorType = null) {
        if (isset($this->errors[$errorType])) {
            return $this->errors[$errorType];
        }

        return $this->errors;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        $xpath = $this->getXPath();

        $parameterElements = $xpath->query($this->parametersXPath);
        $factory = $this->getParameterFactory();
        $parameters = array();
        foreach ($parameterElements as $parameterElement) {
            $parameters[] = $factory->getParameter($parameterElement);
        }

        return $parameters;
    }
}