<?php

namespace MilesChou\Toggle\Contracts;

interface ToggleInterface
{
    /**
     * @return array
     */
    public function all();

    /**
     * @param array|null $context
     * @return mixed
     */
    public function context(array $context = null);

    /**
     * @param string $name
     * @param callable|bool|null $processor
     * @param array $params
     * @param bool|null $static
     * @return static
     */
    public function create($name, $processor = null, array $params = [], $static = null);

    /**
     * @param bool $preserve
     * @return static
     */
    public function duplicate($preserve = false);

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
     * @param array $context
     * @return bool
     */
    public function isActive($name, array $context = []);

    /**
     * @param string $name
     * @param array $context
     * @return bool
     */
    public function isInactive($name, array $context = []);

    /**
     * @return array
     */
    public function names();

    /**
     * @param string $name
     * @param mixed|null $key
     * @param mixed|null $default
     * @return mixed|static
     */
    public function params($name, $key = null, $default = null);

    /**
     * @param string $name
     * @param callable|null $processor
     * @return callable|static
     */
    public function processor($name, $processor = null);

    /**
     * @param string $name
     * @return void
     */
    public function remove($name);

    /**
     * Import / export result data
     *
     * @param array|null $result
     * @return array|static
     */
    public function result(array $result = null);

    /**
     * When $feature on, then call $callable
     *
     * @param string $name
     * @param callable $callback
     * @param callable $default
     * @param array $context
     * @return mixed|static
     */
    public function when($name, callable $callback, callable $default = null, array $context = []);

    /**
     * Unless $feature on, otherwise call $callable
     *
     * @param string $name
     * @param callable $callback
     * @param callable $default
     * @param array $context
     * @return mixed|static
     */
    public function unless($name, callable $callback, callable $default = null, array $context = []);

    /**
     * @param string $name
     * @param FeatureInterface $feature
     * @return static
     */
    public function add($name, FeatureInterface $feature);

    /**
     * @param string $name
     * @return FeatureInterface
     */
    public function feature($name);
}
