<?php

namespace MilesChou\Toggle\Concerns;

trait ParameterAwareTrait
{
    /**
     * @var array
     */
    private $params;

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
     * @return mixed
     */
    public function getParam($name)
    {
        return $this->existParam($name) ? $this->params[$name] : null;
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
}