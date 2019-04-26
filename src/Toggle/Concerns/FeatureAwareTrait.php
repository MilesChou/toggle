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
    public function add($name, FeatureInterface $feature)
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
    public function all()
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
    public function create($name, $processor = null, array $params = [], $staticResult = null)
    {
        return $this->add($name, Feature::create($processor, $params, $staticResult));
    }

    /**
     * @param string $name
     * @return FeatureInterface
     * @throws InvalidArgumentException
     */
    public function feature($name)
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
     * @param Feature|string $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->features);
    }

    /**
     * @param string $name
     */
    public function remove($name)
    {
        unset($this->features[$name]);
    }

    /**
     * @param string $name
     * @param FeatureInterface $feature
     * @return static
     */
    public function set($name, FeatureInterface $feature)
    {
        $this->features[$name] = $feature;

        return $this;
    }

    /**
     * @return array
     */
    public function names()
    {
        return array_keys($this->features);
    }
}
