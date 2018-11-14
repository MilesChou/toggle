<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Contracts\ContextInterface;

trait ContextAwareTrait
{
    /**
     * @var ContextInterface|null
     */
    private $context;

    /**
     * @return ContextInterface|null
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param ContextInterface|null $context
     * @return static
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param ContextInterface|null $context
     * @return ContextInterface|null
     */
    protected function resolveContext($context)
    {
        if (null === $context) {
            $context = $this->context;
        }

        return $context;
    }
}
