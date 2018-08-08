<?php

namespace MilesChou\Toggle;

class Context
{
    /**
     * @var array
     */
    private $params;

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
        return $this->exist($name);
    }

    public function exist($name)
    {
        return isset($this->params[$name]);
    }

    /**
     * @param string $name
     * @return array
     */
    public function get($name)
    {
        return $this->exist($name) ? $this->params[$name] : null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function set($name, $value)
    {
        $this->params[$name] = $value;

        return $this;
    }
}
