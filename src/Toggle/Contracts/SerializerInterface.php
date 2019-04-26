<?php

namespace MilesChou\Toggle\Contracts;

interface SerializerInterface
{
    /**
     * @param string $str
     * @return array
     */
    public function deserialize($str);

    /**
     * @param array $data
     * @return string
     */
    public function serialize($data);
}
