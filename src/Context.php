<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ParameterAwareTrait;
use MilesChou\Toggle\Contracts\ParameterAwareInterface;

class Context implements ParameterAwareInterface
{
    use ParameterAwareTrait;

    /**
     * @param array $params
     * @return static
     */
    public static function create(array $params = [])
    {
        return new static($params);
    }

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function __get($name)
    {
        return $this->getParam($name);
    }

    public function __set($name, $value)
    {
        $this->setParam($name, $value);
    }

    public function __isset($name)
    {
        return $this->existParam($name);
    }
}
