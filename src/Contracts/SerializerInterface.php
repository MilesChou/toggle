<?php

namespace MilesChou\Toggle\Contracts;

interface SerializerInterface
{
    /**
     * @param mixed $data
     * @return string
     */
    public function serialize($data);

    /**
     * @param string $str
     * @return mixed
     */
    public function unserialize($str);
}
