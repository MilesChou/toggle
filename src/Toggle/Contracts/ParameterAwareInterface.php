<?php

namespace MilesChou\Toggle\Contracts;

interface ParameterAwareInterface
{
    /**
     * @param string $key
     * @return bool
     */
    public function hasParam($key);

    /**
     * @param mixed|null $key
     * @param mixed|null $value
     * @return mixed
     */
    public function params($key = null, $value = null);
}
