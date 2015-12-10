<?php

namespace Asuuri\Svg\Parameter;

class Text extends AbstractParameter {
    /**
     * String
     */
    private $value = null;

    /**
     * Set text element value
     *
     * @param String $value Text element value
     * @return \Asuuri\Svg\Parameter\Text
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get text element value
     *
     * @return String
     */
    public function getValue()
    {
        return $this->value;
    }

    public function apply() {
        $value = $this->getValue();

        if ($value !== null) {
            $this->getElement()->textContent = $this->getValue();
        }

        return parent::apply();
    }
}
