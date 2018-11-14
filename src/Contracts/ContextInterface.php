<?php

namespace MilesChou\Toggle\Contracts;

interface ContextInterface
{
    /**
     * @return array
     */
    public function all();

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * @param string $key
     * @return bool
     */
    public function has($key);

    /**
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public function set($key, $value);
}
