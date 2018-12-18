<?php

namespace MilesChou\Toggle\Contracts;

interface FeatureInterface
{
    /**
     * @return bool
     */
    public function hasResult();

    /**
     * @param array $context
     * @return bool
     */
    public function isActive(array $context = []);

    /**
     * @param array|string|null $key
     * @param mixed|null $value
     * @return mixed|static
     */
    public function params($key = null, $value = null);

    /**
     * @param callable|null $processor
     * @return callable|static
     */
    public function processor($processor = null);

    /**
     * @param bool|null $result
     * @return static|bool|null
     */
    public function result($result = null);
}
