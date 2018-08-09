<?php

namespace MilesChou\Toggle\Contracts;

interface ParameterAwareInterface
{
    /**
     * @param string $key
     * @return string
     */
    public function getParam($key);

    /**
     * @param $key
     * @param $value
     * @return static
     */
    public function setParam($key, $value);

    /**
     * @param $key
     * @return bool
     */
    public function existParam($key);
}
