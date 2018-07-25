<?php

namespace MilesChou\Toggle\Traits;

use MilesChou\Toggle\Context;

trait ProcessorAwareTrait
{
    /**
     * @var callable
     */
    private $processor;

    /**
     * @return callable
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    /**
     * @param Context|null $context
     * @return mixed
     */
    public function process($context)
    {
        return call_user_func($this->getProcessor(), $context);
    }

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
}
