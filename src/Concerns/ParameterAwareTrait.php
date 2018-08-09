<?php

namespace MilesChou\Toggle\Concerns;

trait ParameterAwareTrait
{
    /**
     * @var array
     */
    private $params = [];

    /**
     * @param string $name
     * @return bool
     */
    public function existParam($name)
    {
        return isset($this->params[$name]);
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        return $this->existParam($name) ? $this->params[$name] : $default;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;

        return $this;
    }

    /**
     * @param array $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }
}
