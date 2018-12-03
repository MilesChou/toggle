<?php

namespace MilesChou\Toggle\Contracts;

interface FeatureInterface
{
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
}
