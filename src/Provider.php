<?php

namespace MilesChou\Toggle;

interface Provider
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
     * @return static
     */
    public function setFeatures(array $features);

    /**
     * @param array $groups
     * @return static
     */
    public function setGroups(array $groups);
}
