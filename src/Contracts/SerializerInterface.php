<?php

namespace MilesChou\Toggle\Contracts;

interface SerializerInterface
{
    /**
     * @param string $str
     * @return mixed
     */
    public function deserialize($str);

    /**
     * @param mixed $data
     * @return string
     */
    public function serialize($data);
}
