<?php

namespace MilesChou\Toggle;

interface FeatureInterface
{
    /**
     * @return bool
     */
    public function isActive();
}
