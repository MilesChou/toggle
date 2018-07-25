<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Context;

trait ContextTrait
{

    /**
     * @var Context
     */
    private $context;

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

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
