<?php

namespace Asuuri\Svg\Parameter;

abstract class AbstractParameter implements ParameterInterface
{
    /**
     * @var \DOMElement
     */
    protected $element = null;

    /**
     * @var string
     */
    protected $id = null;

    public function __construct(\DOMElement $element = null) {
        if ($element) {
            $this
                ->setElement($element)
                ->setId($element->getAttribute('id'));

        }
    }

    /**
     * @return \DOMElement
     */
    public function getElement() {
        return $this->node;
    }

    /**
     * @param \DOMElement $element
     * @return \Asuuri\Svg\Parameter\Parameter
     */
    public function setElement(\DOMElement $element) {
        $this->node = $element;

        return $this;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $id
     * @return \Asuuri\Svg\Parameter\Parameter
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }
}