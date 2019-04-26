<?php

namespace MilesChou\Toggle\Concerns;

use InvalidArgumentException;
use RuntimeException;

trait ProcessorAwareTrait
{
    /**
     * @var callable
     */
    private $processor;

    /**
     * @param mixed $processor
     */
    protected static function assertProcessor($processor)
    {
        if (!is_callable($processor)) {
            throw new InvalidArgumentException('Processor must be callable');
        }
    }

    /**
     * @param callable|null $processor
     * @return callable|static
     * @throws InvalidArgumentException
     */
    public function processor($processor = null)
    {
        if (null === $processor) {
            return $this->processor;
        }

        static::assertProcessor($processor);

        $this->processor = $processor;

        return $this;
    }

    /**
     * @param array $context
     * @param array $parameters
     * @return mixed
     * @throws RuntimeException
     */
    protected function process(array $context, array $parameters = [])
    {
        $result = call_user_func($this->processor(), $context, $parameters);

        if (!$this->isValidProcessedResult($result)) {
            throw new RuntimeException('Processed result is not valid');
        }

        return $result;
    }

    /**
     * @param mixed $result
     * @return bool
     */
    abstract protected function isValidProcessedResult($result);
}
