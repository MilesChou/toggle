<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ContextTrait;
use MilesChou\Toggle\Concerns\FacadeTrait;
use MilesChou\Toggle\Concerns\FeatureTrait;
use MilesChou\Toggle\Concerns\GroupTrait;

class Manager
{
    use ContextTrait;
    use FacadeTrait;
    use FeatureTrait;
    use GroupTrait;

    /**
     * @param string $featureName
     * @param null|Context $context
     * @return bool
     */
    public function isActive($featureName, Context $context = null)
    {
        if (!array_key_exists($featureName, $this->features)) {
            throw new \InvalidArgumentException("Feature '{$featureName}' is not found");
        }

        if (null === $context) {
            $context = $this->context;
        }

        return $this->features[$featureName]->isActive($context);
    }

    /**
     * @param string $groupName
     * @param null|Context $context
     * @return string
     */
    public function select($groupName, Context $context = null)
    {
        if (!array_key_exists($groupName, $this->group)) {
            throw new \InvalidArgumentException("Group '{$groupName}' is not found");
        }

        if (null === $context) {
            $context = $this->context;
        }

        return $this->group[$groupName]->select($context);
    }
}
