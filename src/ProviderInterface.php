<?php

namespace MilesChou\Toggle;

interface ProviderInterface
{
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
     * @return static
     */
    public function serialize();

    /**
     * @param string $str
     * @return static
     */
    public function unserialize($str);
}
