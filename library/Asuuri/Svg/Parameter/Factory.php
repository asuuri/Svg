<?php

namespace Asuuri\Svg\Parameter;

use Asuuri\Svg\Exception as SvgException;

class Factory
{
    /**
     * @var array
     */
    protected $classMap = array(
        'g'     => '\Asuuri\Svg\Parameter\Group',
        'rect'  => '\Asuuri\Svg\Parameter\Rectangle',
        'text'  => '\Asuuri\Svg\Parameter\Text',
    );

    /**
     * @param string $elementName
     * @return bool
     */
    public function hasElement($tagName)
    {
        return isset($this->classMap[$tagName]);
    }

    /**
     * @return string|null
     */
    public function getParameterClassName($tagName)
    {
        if (isset($this->classMap[$tagName])) {
            return $this->classMap[$tagName];
        }

        return null;
    }

    /**
     * @param \DOMElement $element
     * @return \Asuuri\Svg\Parameter\ParameterInterface
     * @throws SvgException
     */
    public function getParameter(\DOMElement $element)
    {
        $tagName = $element->tagName;
        $className = $this->getParameterClassName($tagName);
        if ($className) {
            return new $className($element);
        } else {
            throw new SvgException(
                sprintf(
                    'Missing class mapping for svg element \'%s\'',
                    $tagName
                )
            );
        }
    }
}