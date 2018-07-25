<?php

namespace MilesChou\Toggle;

class Group
{
    /**
     * @var null|bool
     */
    private $alwaysReturn;

    /**
     * @var callable
     */
    private $condition;

    /**
     * @param callable $condition The callable will return bool
     * @return static
     */
    public static function create(callable $condition)
    {
        return new static($condition);
    }

    /**
     * @param callable $condition
     */
    public function __construct(callable $condition)
    {
        $this->condition = $condition;
    }

    /**
     * @param null|Context $context
     * @return string
     */
    public function select(Context $context = null)
    {
        if (null !== $this->alwaysReturn) {
            return $this->alwaysReturn;
        }

        return call_user_func($this->condition, $context);
    }

    /**
     * @param string $feature
     * @return static
     */
    public function alwaysReturn($feature)
    {
        $this->alwaysReturn = $feature;

        return $this;
    }

    /**
     * @return static
     */
    public function reset()
    {
        $this->alwaysReturn = null;

        return $this;
    }
}
