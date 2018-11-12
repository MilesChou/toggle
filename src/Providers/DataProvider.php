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
    final public function fill(array $features, $context = null)
    {
        return $this->setParams(array_map(function ($feature) use ($context) {
            if ($feature instanceof Feature) {
                return [
                    'params' => $feature->getParams(),
                    'result' => $feature->isActive($context),
                ];
            }

            return $feature;
        }, $features));
    }
}
