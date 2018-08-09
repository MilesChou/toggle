<?php

namespace MilesChou\Toggle\Contracts;

use MilesChou\Toggle\Context;

interface FeatureInterface
{
    /**
     * @param Context|null $context
     * @return bool
     */
    public function isActive($context = null);
}
