<?php

namespace MilesChou\Toggle\Concerns;

use InvalidArgumentException;
use MilesChou\Toggle\Feature;
use RuntimeException;

trait FeatureAwareTrait
{
    /**
     * @var Feature[]
     */
    private $features = [];

    /**
     * @var array
     */
    private $preserveResult = [];

    /**
     * @param Feature $feature
     * @return static
     */
    public function add(Feature $feature)
    {
        $name = $feature->getName();

        if ($this->exist($name)) {
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
     * @param array $features
     * @return static
     */
    public function append(array $features)
    {
        foreach ($features as $feature) {
            $this->add($feature);
        }

        return $this;
    }

    /**
     * @param Feature|string $name
     * @return bool
     */
    public function exist($name)
    {
        if ($name instanceof Feature) {
            $name = $name->getName();
        }

        return array_key_exists($name, $this->features);
    }

    /**
     * @param string $name
     * @param callable|bool|null $processor
     * @param array $params
     * @return static
     */
    public function create($name, $processor = null, array $params = [])
    {
        return $this->add(Feature::create($name, $processor, $params));
    }

    /**
     * Alias of getFeature()
     *
     * @param string $name
     * @return Feature
     */
    public function feature($name)
    {
        return $this->get($name);
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->features = [];
        $this->preserveResult = [];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->features);
    }

    /**
     * @param string $name
     * @return Feature
     * @throws InvalidArgumentException
     */
    public function get($name)
    {
        if (!$this->exist($name)) {
            throw new RuntimeException("Feature '{$name}' is not found");
        }

        return $this->features[$name];
    }

    /**
     * @param string $name
     */
    public function remove($name)
    {
        unset($this->features[$name], $this->preserveResult[$name]);
    }

    /**
     * @param array $features
     * @return static
     */
    public function set(array $features)
    {
        $this->flush();
        $this->append($features);

        return $this;
    }
}
