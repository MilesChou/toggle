<?php

namespace MilesChou\Toggle\Serializers;

use MilesChou\Toggle\Concerns\DataProviderTrait;
use MilesChou\Toggle\Contracts\DataProviderInterface;

abstract class AbstractDataProvider implements DataProviderInterface
{
    use DataProviderTrait;

    /**
     * @param array $features
     * @param array $groups
     */
    public function __construct(array $features = [], array $groups = [])
    {
        $this->setFeatures($features);
        $this->setGroups($groups);
    }
}
