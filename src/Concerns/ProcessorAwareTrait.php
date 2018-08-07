<?php

namespace MilesChou\Toggle\Concerns;

use InvalidArgumentException;
use MilesChou\Toggle\Context;
use RuntimeException;

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
     * @return static
     */
    public function resetResult()
    {
        $this->processedResult = null;

        return $this;
    }

    /**
     * @param mixed $result
     * @return $this
     */
    public function setProcessedResult($result)
    {
        $this->assertResult($result);

        $this->processedResult = $result;

        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function setProcessor($callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Processor must be valid callable');
        }

        $this->processor = $callback;

        return $this;
    }

    /**
     * @param Context $context
     * @return mixed
     */
    protected function process($context)
    {
        if (null !== $this->processedResult) {
            return $this->processedResult;
        }

        if (null === $this->processor) {
            throw new RuntimeException('It\'s must provide a processor to decide feature');
        }

        if (null === $context) {
            $context = Context::create();
        }

        $result = call_user_func($this->getProcessor(), $context);

        $this->assertResult($result);

        $this->processedResult = $result;

        return $this->processedResult;
    }

    /**
     * @param mixed $result
     * @throws InvalidArgumentException
     */
    abstract protected function assertResult($result);
}
