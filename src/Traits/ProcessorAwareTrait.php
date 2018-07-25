<?php

namespace MilesChou\Toggle\Traits;

trait ProcessorAwareTrait
{
    /**
     * @var callable
     */
    private $processor;

    /**
     * @param callable $callback
     * @return $this
     */
    public function setProcessor($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Processor must be valid callable');
        }

        $this->processor = $callback;

        return $this;
    }

    /**
     * @return callable
     */
    public function getProcessor()
    {
        return $this->processor;
    }
}
