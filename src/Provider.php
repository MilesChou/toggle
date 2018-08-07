<?php

namespace MilesChou\Toggle;

interface Provider
{
    const SERIALIZE_TYPE_JSON = 0;
    const SERIALIZE_TYPE_NATIVE = 1;

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

    /**
     * @param int $type
     * @return static
     */
    public function serialize();

    /**
     * @param string $str
     * @param int $type
     * @return static
     */
    public function unserialize($str);
}
