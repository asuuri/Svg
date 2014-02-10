<?php

namespace Asuuri\Svg\Model;

class Svg {

    /**
     * @var \DOMDocument
     */
    protected $svgDom;

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
}