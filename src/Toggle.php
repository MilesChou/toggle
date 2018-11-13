<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ContextAwareTrait;
use MilesChou\Toggle\Concerns\FeatureAwareTrait;
use MilesChou\Toggle\Providers\ResultProvider;
use RuntimeException;

class Toggle
{
    use ContextAwareTrait;
    use FeatureAwareTrait;

    /**
     * @var bool
     */
    private $preserve = true;

    /**
     * @var bool
     */
    private $strict = false;

    /**
     * @param string $name
     * @param null|Context $context
     * @return bool
     */
    public function isActive($name, Context $context = null)
    {
        if (!$this->has($name)) {
            if ($this->strict) {
                throw new RuntimeException("Feature '{$name}' is not found");
            }

            return false;
        }

        $feature = $this->feature($name);

        if ($feature->hasResult()) {
            return $feature->result();
        }

        if (isset($this->preserveResult[$name])) {
            return $this->preserveResult[$name];
        }

        $context = $this->resolveContext($context);
        $result = $feature->isActive($context);

        if ($this->preserve) {
            $this->preserveResult[$name] = $result;
        }

        return $result;
    }

    /**
     * Import / export result data
     *
     * @param ResultProvider|null $resultProvider
     * @return ResultProvider
     */
    public function result(ResultProvider $resultProvider = null)
    {
        if (null === $resultProvider) {
            return new ResultProvider($this->preserveResult);
        }

        $this->preserveResult = array_merge($this->preserveResult, $resultProvider->toArray());

        return $resultProvider;
    }

    /**
     * @param bool $preserve
     * @return static
     */
    public function setPreserve($preserve)
    {
        $this->preserve = $preserve;

        return $this;
    }

    /**
     * @param bool $strict
     * @return static
     */
    public function setStrict($strict)
    {
        $this->strict = $strict;

        return $this;
    }

    /**
     * When $feature on, then call $callable
     *
     * @param string $name
     * @param callable $callable
     * @param Context|null $context
     *
     * @return static
     */
    public function when($name, callable $callable, Context $context = null)
    {
        $feature = $this->get($name);

        if ($this->isActive($feature, $context)) {
            $callable($this->resolveContext($context), $feature->getParams());
        }

        return $this;
    }
}
