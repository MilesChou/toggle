<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ParameterAwareTrait;
use MilesChou\Toggle\Contracts\ContextInterface as ContextContract;
use MilesChou\Toggle\Contracts\ParameterAwareInterface;

class Context implements ContextContract, ParameterAwareInterface
{
    use ParameterAwareTrait {
        getParam as get;
        getParams as all;
        hasParam as has;
        setParam as set;
    }

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
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __isset($name)
    {
        return $this->has($name);
    }
}
