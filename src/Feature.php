<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Traits\ProcessorAwareTrait;

class Feature
{
    use ProcessorAwareTrait;

    /**
     * @var null|bool
     */
    private $alwaysReturn;

    /**
     * @var bool
     */
    private $processedReturn;

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
        if (null !== $this->processedReturn) {
            return $this->processedReturn;
        }

        $this->processedReturn = (null === $this->alwaysReturn)
            ? call_user_func($this->getProcessor(), $context)
            : $this->alwaysReturn;

        if (!is_bool($this->processedReturn)) {
            throw new \RuntimeException('Processed result must be bool');
        }

        return $this->processedReturn;
    }

    /**
     * @return static
     */
    public function off()
    {
        $this->alwaysReturn = false;

        return $this;
    }

    /**
     * @return static
     */
    public function on()
    {
        $this->alwaysReturn = true;

        return $this;
    }

    /**
     * @return static
     */
    public function reset()
    {
        $this->alwaysReturn = null;

        return $this;
    }
}
