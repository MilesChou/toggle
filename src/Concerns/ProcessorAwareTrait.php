<?php

namespace MilesChou\Toggle\Concerns;

use InvalidArgumentException;
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
     * @param callable|null $processor
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setProcessor($processor)
    {
        if (!is_callable($processor)) {
            throw new InvalidArgumentException('Processor must be callable');
        }

        $this->processor = $processor;

        return $this;
    }

    /**
     * @param Context|null $context
     * @param array $parameters
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function process($context, array $parameters = [])
    {
        if (null === $context) {
            $context = Context::create();
        }

        $result = call_user_func($this->getProcessor(), $context, $parameters);

        if (!$this->isValidProcessedResult($result)) {
            throw new InvalidArgumentException('Processed result is not valid');
        }

        return $result;
    }

    /**
     * @param mixed $result
     * @return bool
     */
    abstract protected function isValidProcessedResult($result);
}
