<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Contracts\ToggleInterface;
use RuntimeException;

class Toggle implements ToggleInterface
{
    use Concerns\ContextAwareTrait;
    use Concerns\FeatureAwareTrait {
        remove as private removeFeature;
        flush as private flushFeature;
    }
    use Concerns\PersistentTrait;

    /**
     * @var array
     */
    private $preserveResult = [];

    /**
     * @var bool
     */
    private $strict = false;

    /**
     * @param bool $preserve
     * @return static
     */
    public function duplicate($preserve = false)
    {
        $clone = clone $this;

        if (!$preserve) {
            $clone->preserveResult = [];
        }

        return $clone;
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->flushFeature();

        $this->preserveResult = [];
    }

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

        if (empty($context)) {
            $context = $this->context();
        }

        if (!isset($this->preserveResult[$name])) {
            $this->preserveResult[$name] = $feature->isActive($context);
        }

        return $this->preserveResult[$name];
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
    public function params($name, $key = null, $default = null)
    {
        return $this->feature($name)->params($key, $default);
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
     * @param string $name
     */
    public function remove($name)
    {
        $this->removeFeature($name);

        unset($this->preserveResult[$name]);
    }

    /**
     * @inheritdoc
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
        }

        if ($default) {
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
        }

        if ($default) {
            return $default($feature, $context);
        }

        return $this;
    }
}
