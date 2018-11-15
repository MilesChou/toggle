<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Contracts\ContextInterface;
use MilesChou\Toggle\Contracts\FeatureInterface;
use MilesChou\Toggle\Contracts\RunnerInterface;

class Runner implements RunnerInterface
{
    /**
     * @var bool
     */
    private $condition;

    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * @var FeatureInterface
     */
    private $feature;

    /**
     * @param FeatureInterface $feature
     */
    public function __construct(FeatureInterface $feature)
    {
        $this->feature = $feature;
    }

    /**
     * @param ContextInterface|null $context
     * @return static
     */
    public function isActive(ContextInterface $context = null)
    {
        $this->context = $context;
        $this->condition = true;

        return $this;
    }

    /**
     * @param ContextInterface|null $context
     * @return static
     */
    public function isInactive(ContextInterface $context = null)
    {
        $this->context = $context;
        $this->condition = false;

        return $this;
    }

    public function then(callable $positive, callable $negative = null)
    {
        if ($this->condition === $this->feature->isActive($this->context)) {
            return $positive($this->feature, $this->context);
        }

        if (null !== $negative) {
            return $negative($this->feature, $this->context);
        }
    }
}
