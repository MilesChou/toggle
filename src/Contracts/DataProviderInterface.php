<?php

namespace MilesChou\Toggle\Contracts;

use MilesChou\Toggle\Context;

interface DataProviderInterface
{
    /**
     * @param array $data
     * @param Context|null $context
     * @return static
     */
    public function fill(array $data, $context = null);

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

    /**
     * @return array
     */
    public function toArray();
}
