<?php

namespace ide\editors\support;


use php\gui\UXImage;

class Gutter
{
    /**
     * @var UXImage
     */
    private $image;

    /**
     * @var callable
     */
    private $callback;
    
    public function __construct(UXImage $image, callable $callback)
    {
        $this->image    = $image;
        $this->callback = $callback;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return UXImage
     */
    public function getImage()
    {
        return $this->image;
    }
}