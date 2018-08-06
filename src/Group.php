<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Traits\ProcessorAwareTrait;

class Group
{
    use ProcessorAwareTrait;

    /**
     * @param callable $processor The callable will return bool
     * @return static
     */
    public static function create(callable $processor)
    {
        return new static($processor);
    }

    /**
     * @param callable $processor
     */
    public function __construct(callable $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param null|Context $context
     * @return string
     */
    public function select(Context $context = null)
    {
        return $this->process($context);
    }

    protected function assertResult($result)
    {
        // Pass
    }
}
