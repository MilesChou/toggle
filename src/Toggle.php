<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ContextAwareTrait;
use MilesChou\Toggle\Concerns\FeatureAwareTrait;
use MilesChou\Toggle\Contracts\ToggleInterface;
use RuntimeException;

class Toggle implements ToggleInterface
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
     * {@inheritdoc}
     */
    public function isActive($name, array $context = [])
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
     * {@inheritdoc}
     */
    public function isInactive($name, array $context = [])
    {
        return !$this->isActive($name, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function params($name, $key = null)
    {
        $params = $this->feature($name)->params($key);

        if (is_array($key)) {
            return $this;
        }

        return $params;
    }

    /**
     * {@inheritdoc}
     */
    public function processor($name, $processor = null)
    {
        $result = $this->feature($name)->processor($processor);

        if (null === $processor) {
            return $result;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function result(array $result = null)
    {
        if (null === $result) {
            return array_reduce($this->names(), function ($carry, $feature) {
                $carry[$feature] = isset($this->preserveResult[$feature])
                    ? $this->preserveResult[$feature]
                    : $this->isActive($feature);

                return $carry;
            }, []);
        }

        $this->preserveResult = array_merge($this->preserveResult, $result);

        return $this;
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
     * {@inheritdoc}
     */
    public function when($name, callable $callback, callable $default = null, array $context = [])
    {
        $feature = $this->feature($name);

        if ($this->isActive($name, $context)) {
            return $callback($feature, $context);
        } elseif ($default) {
            return $default($feature, $context);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function unless($name, callable $callback, callable $default = null, array $context = [])
    {
        $feature = $this->feature($name);

        if ($this->isInactive($name, $context)) {
            return $callback($feature, $context);
        } elseif ($default) {
            return $default($feature, $context);
        }

        return $this;
    }
}
