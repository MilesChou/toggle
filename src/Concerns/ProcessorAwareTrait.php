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
     * @param callable|null $processor
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setProcessor($processor)
    {
        if (!$this->isValidProcessor($processor)) {
            throw new InvalidArgumentException('Processor must be callable');
        }

        $this->processor = $processor;

        return $this;
    }

    /**
     * @param mixed $result
     * @throws InvalidArgumentException
     */
    protected function assertResult($result)
    {
        if (!$this->isValidProcessedResult($result)) {
            throw new InvalidArgumentException('Processed result is not valid');
        }
    }

    /**
     * @param mixed $processor
     */
    protected function init($processor)
    {
        if ($this->isValidProcessor($processor)) {
            $this->setProcessor($processor);
            return;
        }

        if ($this->isValidProcessedResult($processor)) {
            $this->setProcessedResult($processor);
            return;
        }

        throw new InvalidArgumentException('Processor is not valid processor or result');
    }

    /**
     * @param mixed $processor
     * @return bool
     */
    protected function isValidProcessor($processor)
    {
        return null === $processor || is_callable($processor);
    }

    /**
     * @param Context|null $context
     * @return mixed
     * @throws RuntimeException
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

        return $result;
    }

    /**
     * @param mixed $result
     * @return bool
     */
    abstract protected function isValidProcessedResult($result);
}
