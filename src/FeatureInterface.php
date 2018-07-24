<?php

namespace MilesChou\Toggle;

interface FeatureInterface
{
    /**
     * @param Context|null $context
     * @return bool
     */
    public function isActive(Context $context = null);
}
