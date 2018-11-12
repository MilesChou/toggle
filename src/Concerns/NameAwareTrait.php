<?php

namespace MilesChou\Toggle\Concerns;

trait NameAwareTrait
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param null|string $name
     * @return string|static
     */
    public function name($name = null)
    {
        if (null === $name) {
            return $this->name;
        }

        $this->name = $name;

        return $this;
    }
}
