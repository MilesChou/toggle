<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Context;

trait ContextAwareTrait
{
    /**
     * @var Context|null
     */
    private $context;

    /**
     * @return Context|null
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Context|null $context
     * @return static
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param Context|null $context
     * @return Context|null
     */
    protected function resolveContext($context)
    {
        if (null === $context) {
            $context = $this->context;
        }

        return $context;
    }
}
