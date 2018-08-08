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
     * @param Context|null $context
     * @return SerializerInterface
     */
    public function export($providerDriver, Context $context = null)
    {
        if (!class_exists($providerDriver)) {
            throw new RuntimeException("Unknown class {$providerDriver}");
        }

        /** @var SerializerInterface $persistentProvider */
        $persistentProvider = new $providerDriver();

        if (!$persistentProvider instanceof SerializerInterface) {
            throw new RuntimeException('Driver must instance of Provider');
        }

        $context = $this->resolveContext($context);

        return $persistentProvider
            ->setFeatures($this->features, $context)
            ->setGroups($this->groups, $context);
    }

    /**
     * @param SerializerInterface $persistentProvider
     * @param bool $clean
     */
    public function import(SerializerInterface $persistentProvider, $clean = true)
    {
        if ($clean) {
            $this->cleanFeature();
            $this->cleanGroup();
        }

        foreach ($persistentProvider->getFeatures() as $name => $feature) {
            $this->createFeature($name, $feature['r']);
        }

        foreach ($persistentProvider->getGroups() as $name => $group) {
            $this->createGroup($name, $group['l'], $group['r']);
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

        $context = $this->resolveContext($context);

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

        $context = $this->resolveContext($context);

        return $this->groups[$groupName]->select($context);
    }

    /**
     * @param Context|null $context
     * @return Context|null
     */
    protected function resolveContext($context)
    {
        if (null === $context) {
            $context = $this->context;
        }

        return $context;
    }
}
