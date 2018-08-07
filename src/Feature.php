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
        $this->init($processor);
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
        if (!$this->isValidProcessedResult($result)) {
            throw new \RuntimeException('Processed result must be bool');
        }
    }

    /**
     * @param mixed $result
     * @return bool
     */
    protected function isValidProcessedResult($result)
    {
        return is_bool($result);
    }
}
