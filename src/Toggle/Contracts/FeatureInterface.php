<?php

namespace MilesChou\Toggle\Contracts;

interface FeatureInterface
{
    /**
     * Set the feature flag disable
     *
     * @return FeatureInterface
     */
    public function disable(): FeatureInterface;

    /**
     * Set the feature flag enable
     *
     * @return FeatureInterface
     */
    public function enable(): FeatureInterface;

    /**
     * Get the persistent flag
     *
     * @return bool|null
     */
    public function flag(): ?bool;

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
     * Persist the toggle flags
     *
     * @param bool $flag
     * @return callable|static
     */
    public function persist(bool $flag);

    /**
     * @param callable|null $processor
     * @return callable|static
     */
    public function processor($processor = null);
}
