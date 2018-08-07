<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Traits\ProcessorAwareTrait;

class Feature
{
    use ProcessorAwareTrait;

    /**
     * @param callable $processor The callable will return bool
     * @return static
     */
    public static function create(callable $processor = null)
    {
        return new static($processor);
    }

    /**
     * @param callable $processor
     */
    public function __construct(callable $processor = null)
    {
        if (null !== $processor) {
            $this->setProcessor($processor);
        }
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

    public function serialize()
    {
    }
}
