<?php

namespace MilesChou\Toggle\Concerns;

use InvalidArgumentException;
use MilesChou\Toggle\Contracts\FeatureInterface;
use MilesChou\Toggle\Feature;
use RuntimeException;

trait FeatureAwareTrait
{
    /**
     * @var FeatureInterface[]
     */
    private $features = [];

    /**
     * @param string $name
     * @param FeatureInterface $feature
     * @return static
     */
    public function add(string $name, FeatureInterface $feature)
    {
        if ($this->has($name)) {
            throw new RuntimeException("Feature '{$name}' is exist");
        }

        $this->features[$name] = $feature;

        return $this;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->features;
    }

    /**
     * @param string $name
     * @param callable|bool|null $processor
     * @param array $params
     * @param bool|null $staticResult
     * @return static
     */
    public function create(string $name, $processor = null, array $params = [], ?bool $staticResult = null)
    {
        return $this->add($name, Feature::create($processor, $params, $staticResult));
    }

    /**
     * @param string $name
     * @return FeatureInterface
     * @throws InvalidArgumentException
     */
    public function feature(string $name): FeatureInterface
    {
        if (!$this->has($name)) {
            throw new RuntimeException("Feature '{$name}' is not found");
        }

        return $this->features[$name];
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->features = [];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->features);
    }

    /**
     * @param string $name
     */
    public function remove(string $name)
    {
        unset($this->features[$name]);
    }

    /**
     * @param string $name
     * @param FeatureInterface $feature
     * @return static
     */
    public function set(string $name, FeatureInterface $feature)
    {
        $this->features[$name] = $feature;

        return $this;
    }

    /**
     * @return array
     */
    public function names(): array
    {
        return array_keys($this->features);
    }
}
