<?php

namespace MilesChou\Toggle;

class Feature implements FeatureInterface
{
    /**
     * @var null|bool
     */
    private $alwaysReturn;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if (null !== $this->alwaysReturn) {
            return $this->alwaysReturn;
        }
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
