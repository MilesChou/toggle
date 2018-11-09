<?php

namespace MilesChou\Toggle\Providers;

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Feature;

class DataProvider extends Provider
{
    /**
     * @param array $features
     * @param Context|null $context
     * @return static
     */
    final public function setFeatures(array $features, $context = null)
    {
        $this->features = array_map(function ($feature) use ($context) {
            if ($feature instanceof Feature) {
                return [
                    'params' => $feature->getParams(),
                    'return' => $feature->isActive($context),
                ];
            }

            return $feature;
        }, $features);

        return $this;
    }
}
