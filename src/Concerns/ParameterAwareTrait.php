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
     * @param mixed|null $default
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        return $this->hasParam($name) ? $this->params[$name] : $default;
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
     * @return bool
     */
    public function hasParam($name)
    {
        return isset($this->params[$name]);
    }

    /**
     * @param mixed|null $key
     * @param mixed|null $value
     * @return mixed
     */
    public function params($key = null, $value = null)
    {
        if (null === $key) {
            return $this->getParams();
        }

        if (is_array($key)) {
            return $this->setParams(array_merge($this->params, $key));
        }

        if (null === $value) {
            return $this->getParam($key);
        }

        return $this->setParam($key, $value);
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
