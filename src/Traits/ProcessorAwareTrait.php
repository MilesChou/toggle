<?php

namespace MilesChou\Toggle\Traits;

use MilesChou\Toggle\Context;

trait ProcessorAwareTrait
{
    /**
     * @var mixed
     */
    private $processedResult;

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
     * @param Context|null $context
     * @return mixed
     */
    protected function process($context)
    {
        if (null !== $this->processedResult) {
            return $this->processedResult;
        }

        $result = call_user_func($this->getProcessor(), $context);

        $this->assertResult($result);

        $this->processedResult = $result;

        return $this->processedResult;
    }

    /**
     * @param mixed $result
     * @throws \InvalidArgumentException
     */
    abstract protected function assertResult($result);
}
