<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ContextTrait;
use MilesChou\Toggle\Concerns\FacadeTrait;
use MilesChou\Toggle\Concerns\FeatureTrait;
use MilesChou\Toggle\Concerns\GroupTrait;
use RuntimeException;

class Manager
{
    use ContextTrait;
    use FacadeTrait;
    use FeatureTrait;
    use GroupTrait;

    /**
     * @param string $providerDriver
     * @return ProviderInterface
     */
    public function export($providerDriver)
    {
        if (!class_exists($providerDriver)) {
            throw new RuntimeException("Unknown class {$providerDriver}");
        }

        /** @var ProviderInterface $persistentProvider */
        $persistentProvider = new $providerDriver();

        if (!$persistentProvider instanceof ProviderInterface) {
            throw new RuntimeException('Driver must instance of Provider');
        }

        return $persistentProvider
            ->setFeatures($this->features)
            ->setGroups($this->groups);
    }

    /**
     * @param ProviderInterface $persistentProvider
     * @param bool $clean
     */
    public function import(ProviderInterface $persistentProvider, $clean = true)
    {
        if ($clean) {
            $this->cleanFeature();
            $this->cleanGroup();
        }

        foreach ($persistentProvider->getFeatures() as $name => $feature) {
            $this->createFeature($name, $feature['result']);
        }

        foreach ($persistentProvider->getGroups() as $name => $group) {
            $this->createGroup($name, $group['list'], $group['result']);
        }
    }

    /**
     * @param string $featureName
     * @param null|Context $context
     * @return bool
     */
    public function isActive($featureName, Context $context = null)
    {
        if (!array_key_exists($featureName, $this->features)) {
            throw new RuntimeException("Feature '{$featureName}' is not found");
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
        if (!array_key_exists($groupName, $this->groups)) {
            throw new RuntimeException("Group '{$groupName}' is not found");
        }

        if (null === $context) {
            $context = $this->context;
        }

        return $this->groups[$groupName]->select($context);
    }
}
