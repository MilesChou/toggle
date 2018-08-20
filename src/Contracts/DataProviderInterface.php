<?php

namespace MilesChou\Toggle\Contracts;

use MilesChou\Toggle\Context;

interface DataProviderInterface
{
    const FEATURE = 'feature';
    const FEATURE_PARAMS = 'params';
    const FEATURE_RETURN = 'return';
    const GROUP = 'group';
    const GROUP_LIST = 'list';
    const GROUP_PARAMS = 'params';
    const GROUP_RETURN = 'return';

    /**
     * @return array
     */
    public function getFeatures();

    /**
     * @return array
     */
    public function getGroups();

    /**
     * @param array $features
     * @param Context|null $context
     * @return static
     */
    public function setFeatures(array $features, $context = null);

    /**
     * @param array $groups
     * @param Context|null $context
     * @return static
     */
    public function setGroups(array $groups, $context = null);
}
