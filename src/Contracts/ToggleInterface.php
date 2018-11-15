<?php

namespace MilesChou\Toggle\Contracts;

use RuntimeException;

interface ToggleInterface
{
    /**
     * @param string $name
     * @param FeatureInterface $feature
     * @return static
     */
    public function add($name, FeatureInterface $feature);

    /**
     * @return array
     */
    public function all();

    /**
     * @param string $name
     * @param callable|bool|null $processor
     * @param array $params
     * @param bool|null $staticResult
     * @return static
     */
    public function create($name, $processor = null, array $params = [], $staticResult = null);

    /**
     * @param string $name
     * @return FeatureInterface
     * @throws RuntimeException
     */
    public function feature($name);

    /**
     * @return void
     */
    public function flush();

    /**
     * @param string $name
     * @return bool
     */
    public function has($name);

    /**
     * @param string $name
     * @param ContextInterface|null $context
     * @return bool
     */
    public function isActive($name, ContextInterface $context = null);

    /**
     * @return array
     */
    public function names();

    /**
     * @param string $name
     */
    public function remove($name);

    /**
     * Import / export result data
     *
     * @param ProviderInterface|null $result
     * @return array
     */
    public function result(ProviderInterface $result = null);

    /**
     * @param string $name
     * @param FeatureInterface $feature
     * @return static
     */
    public function set($name, FeatureInterface $feature);

    /**
     * @param string $name
     *
     * @return RunnerInterface
     */
    public function when($name);
}
