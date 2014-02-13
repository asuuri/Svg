<?php

namespace Asuuri\Svg\Parameter;

interface ParameterInterface
{
    /**
     * @param \DOMElement $node
     */
    public function setElement(\DOMElement $element);

    /**
     * @return \DOMElement
     */
    public function getElement();

    /**
     * @var string $id
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getId();
}