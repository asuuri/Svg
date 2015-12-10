<?php

namespace Asuuri\Svg\Options;

/**
 * Render
 *
 * @author eino
 */
class Render {
    const FORMAT_PNG = 'PNG';
    const FORMAT_PPM = 'PPM';

    private $format;

    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }
}
