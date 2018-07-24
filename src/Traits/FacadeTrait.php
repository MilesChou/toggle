<?php

namespace MilesChou\Toggle\Traits;

use RuntimeException;

trait FacadeTrait
{
    /**
     * The globally instance.
     *
     * @var object
     */
    protected static $instance;

    /**
     * Handler all of static call
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if (!self::$instance) {
            throw new RuntimeException('A facade root has not been set.');
        }

        return call_user_func_array([self::$instance, $method], $args);
    }

    /**
     * Make this capsule instance available globally.
     *
     * @return void
     */
    public function setAsGlobal()
    {
        static::$instance = $this;
    }
}
