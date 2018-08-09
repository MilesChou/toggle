<?php

namespace MilesChou\Toggle\Contracts;

interface ParameterAwareInterface
{
    /**
     * @param string $key
     * @return bool
     */
    public function existParam($key);

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function getParam($name, $default = null);

    /**
     * @return mixed
     */
    public function getParams();

    /**
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public function setParam($key, $value);
}
