<?php

namespace MilesChou\Toggle\Concerns;

trait ContextAwareTrait
{
    /**
     * @var array
     */
    private $context = [];

    /**
     * @param array|null $context
     * @return mixed
     */
    public function context(array $context = null)
    {
        if (null === $context) {
            return $this->context;
        }

        $this->context = $context;

        return $this;
    }
}
