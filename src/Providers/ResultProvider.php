<?php

namespace MilesChou\Toggle\Providers;

use MilesChou\Toggle\Context;
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
        $this->features[$name] = $result;

        return $this;
    }

    /**
     * @param array $features
     * @param Context|null $context
     * @return static
     */
    final public function setFeatures(array $features, $context = null)
    {
        $this->features = array_map(function ($feature) use ($context) {
            if ($feature instanceof Feature) {
                return $feature->isActive($context);
            }

            return $feature;
        }, $features);

        return $this;
    }
}
