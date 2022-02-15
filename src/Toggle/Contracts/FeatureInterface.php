<?php

namespace MilesChou\Toggle\Contracts;

interface FeatureInterface
{
    /**
     * Set the feature disable
     *
     * @return FeatureInterface
     */
    public function disable(): FeatureInterface;

    /**
     * Set the feature enable
     *
     * @return FeatureInterface
     */
    public function enable(): FeatureInterface;

    /**
     * @return bool
     */
    public function hasResult(): bool;

    /**
     * @param array $context
     * @return bool
     */
    public function isActive(array $context = []): bool;

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
