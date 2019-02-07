<?php

namespace ide\editors;


class Token
{
    /**
     * @var int
     */
    private $size;

    /**
     * @var int[]
     */
    private $types;

    public function __construct(int $size, int ... $types)
    {
        $this->size  = $size;
        $this->types = $types;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }
}