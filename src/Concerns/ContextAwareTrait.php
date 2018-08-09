<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Context;

trait ContextAwareTrait
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     * @return static
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }
}
