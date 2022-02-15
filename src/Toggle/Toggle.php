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
    public function duplicate(bool $preserve = false): ToggleInterface
    {
        $clone = clone $this;

        if (!$preserve) {
            $clone->preserveResult = [];
        }

        return $clone;
    }

    public function flush()
    {
        $this->flushFeature();

        $this->preserveResult = [];
    }

    public function isActive(string $name, array $context = []): bool
    {
        if (!$this->has($name)) {
            if ($this->strict) {
                throw new RuntimeException("Feature '{$name}' is not found");
            }

            return false;
        }

        $feature = $this->feature($name);

        if ($feature->hasResult()) {
            return $feature->flag();
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

    public function isInactive(string $name, array $context = []): bool
    {
        return !$this->isActive($name, $context);
    }

    public function params(string $name, $key = null, $default = null)
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
    public function remove(string $name)
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
                $carry[$feature] = $this->preserveResult[$feature] ?? $this->isActive($feature);

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
    public function setStrict(bool $strict): ToggleInterface
    {
        $this->strict = $strict;

        return $this;
    }

    public function when(string $name, callable $callback, ?callable $default = null, array $context = [])
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

    public function unless(string $name, callable $callback, ?callable $default = null, array $context = [])
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
