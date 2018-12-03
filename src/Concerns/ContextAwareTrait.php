<?php

namespace MilesChou\Toggle\Concerns;

trait ContextAwareTrait
{
    /**
     * @var array
     */
    private $context = [];

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param array $context
     * @return static
     */
    public function setContext(array $context = [])
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param array|null $context
     * @return array
     */
    protected function resolveContext(array $context = [])
    {
        if (empty($context)) {
            $context = $this->context;
        }

        return $context;
    }
}
