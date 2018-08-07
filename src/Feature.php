<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ProcessorAwareTrait;

class Feature
{
    use ProcessorAwareTrait;

    /**
     * @param callable $processor
     * @return static
     */
    public static function create($processor = null)
    {
        return new static($processor);
    }

    /**
     * @param callable|bool|null $processor The callable will return bool
     */
    public function __construct($processor = null)
    {
        if (null === $processor) {
            return;
        }

        if (is_callable($processor)) {
            $this->setProcessor($processor);
            return;
        }

        if (is_bool($processor)) {
            $this->setProcessedResult($processor);
            return;
        }

        throw new \InvalidArgumentException('The Feature\'s processor must be callable or bool result');
    }

    /**
     * @param Context|null $context
     * @return bool
     */
    public function isActive(Context $context = null)
    {
        return $this->process($context);
    }

    /**
     * @return static
     */
    public function off()
    {
        $this->setProcessedResult(false);

        return $this;
    }

    /**
     * @return static
     */
    public function on()
    {
        $this->setProcessedResult(true);

        return $this;
    }

    protected function assertResult($result)
    {
        if (!is_bool($result)) {
            throw new \RuntimeException('Processed result must be bool');
        }
    }
}
