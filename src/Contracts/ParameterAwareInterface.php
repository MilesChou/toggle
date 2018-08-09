<?php

namespace MilesChou\Toggle\Contracts;

interface ParameterAwareInterface
{
    /**
     * @param string $key
     * @return mixed
     */
    public function getParam($key);

    /**
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public function setParam($key, $value);

    /**
     * @param string $key
     * @return bool
     */
    public function existParam($key);
}
