<?php

namespace MilesChou\Toggle;

class Feature implements FeatureInterface
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

    public function isActive(Context $context = null)
    {
        if (null !== $this->alwaysReturn) {
            return $this->alwaysReturn;
        }

        return call_user_func($this->condition, $context);
    }

    /**
     * @return static
     */
    public function off()
    {
        $this->alwaysReturn = false;

        return $this;
    }

    /**
     * @return static
     */
    public function on()
    {
        $this->alwaysReturn = true;

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
