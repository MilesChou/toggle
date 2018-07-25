<?php

namespace MilesChou\Toggle;

class Context
{
    /**
     * @var array
     */
    private $parameters;

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
        $this->parameters = $params;
    }

    /**
     * @param string $key
     * @return array
     */
    public function getParam($key)
    {
        return $this->parameters[$key];
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->parameters;
    }

    /**
     * @param string $key
     * @param mixed $parameter
     * @return static
     */
    public function setParam($key, $parameter)
    {
        $this->parameters[$key] = $parameter;

        return $this;
    }
}
