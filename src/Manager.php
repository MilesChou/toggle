<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ContextAwareTrait;
use MilesChou\Toggle\Concerns\FacadeTrait;
use MilesChou\Toggle\Concerns\FeatureAwareTrait;
use MilesChou\Toggle\Concerns\GroupAwareTrait;
use MilesChou\Toggle\Contracts\DataProviderInterface;
use RuntimeException;

class Manager
{
    use ContextAwareTrait;
    use FacadeTrait;
    use FeatureAwareTrait;
    use GroupAwareTrait;

    /**
     * @param string $dataProviderDriver
     * @param Context|null $context
     * @return DataProviderInterface
     */
    public function export(Context $context = null, $dataProviderDriver = 'MilesChou\Toggle\DataProvider')
    {
        if (!class_exists($dataProviderDriver)) {
            throw new RuntimeException("Unknown class {$dataProviderDriver}");
        }

        /** @var DataProviderInterface $dataProvider */
        $dataProvider = new $dataProviderDriver();

        if (!$dataProvider instanceof DataProviderInterface) {
            throw new RuntimeException('Driver must instance of Provider');
        }

        $context = $this->resolveContext($context);

        return $dataProvider
            ->setFeatures($this->features, $context)
            ->setGroups($this->groups, $context);
    }

    /**
     * @param DataProviderInterface $dataProvider
     * @param bool $clean
     */
    public function import(DataProviderInterface $dataProvider, $clean = true)
    {
        if ($clean) {
            $this->cleanFeature();
            $this->cleanGroup();
        }

        foreach ($dataProvider->getFeatures() as $name => $feature) {
            $this->createFeature($name, $feature['r']);
        }

        foreach ($dataProvider->getGroups() as $name => $group) {
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
}
