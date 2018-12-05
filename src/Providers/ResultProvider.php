<?php

namespace MilesChou\Toggle\Providers;

use MilesChou\Toggle\Feature;

class ResultProvider extends Provider
{
    /**
     * @param string $name
     * @param bool $result
     * @return static
     */
    public function feature($name, $result)
    {
        return $this->setParam($name, $result);
    }

    /**
     * @param array $features
     * @param array  $context
     * @return static
     */
    final public function fill(array $features, array $context = null)
    {
        return $this->setParams(array_map(function ($feature) use ($context) {
            if ($feature instanceof Feature) {
                return $feature->isActive($context);
            }

            return $feature;
        }, $features));
    }
}
