<?php

namespace MilesChou\Toggle\Contracts;

interface ToggleInterface
{
    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param array|null $context
     * @return mixed
     */
    public function context(array $context = null);

    /**
     * @param string $name
     * @param callable|bool|null $processor
     * @param array $params
     * @param bool|null $staticResult
     * @return static
     */
    public function create(string $name, $processor = null, array $params = [], ?bool $staticResult = null);

    /**
     * @param bool $preserve
     * @return static
     */
    public function duplicate(bool $preserve = false): ToggleInterface;

    /**
     * @return void
     */
    public function flush();

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param string $name
     * @param array $context
     * @return bool
     */
    public function isActive(string $name, array $context = []): bool;

    /**
     * @param string $name
     * @param array $context
     * @return bool
     */
    public function isInactive(string $name, array $context = []): bool;

    /**
     * @return array
     */
    public function names(): array;

    /**
     * @param string $name
     * @param mixed|null $key
     * @param mixed|null $default
     * @return mixed|static
     */
    public function params(string $name, $key = null, $default = null);

    /**
     * @param string $name
     * @param callable|null $processor
     * @return callable|static
     */
    public function processor(string $name, ?callable $processor = null);

    /**
     * @param string $name
     * @return void
     */
    public function remove(string $name);

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
     * @param callable|null $default
     * @param array $context
     * @return mixed|static
     */
    public function when(string $name, callable $callback, ?callable $default = null, array $context = []);

    /**
     * Unless $feature on, otherwise call $callable
     *
     * @param string $name
     * @param callable $callback
     * @param callable|null $default
     * @param array $context
     * @return mixed|static
     */
    public function unless(string $name, callable $callback, ?callable $default = null, array $context = []);

    /**
     * @param string $name
     * @param FeatureInterface $feature
     * @return static
     */
    public function add(string $name, FeatureInterface $feature);

    /**
     * @param string $name
     * @return FeatureInterface
     */
    public function feature(string $name);
}
