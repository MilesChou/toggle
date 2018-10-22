<?php

namespace MilesChou\Toggle\Contracts;

interface SerializerInterface
{
    /**
     * @param string $str
     * @param ProviderInterface $provider
     * @return ProviderInterface
     */
    public function deserialize($str, ProviderInterface $provider);

    /**
     * @param ProviderInterface $provider
     * @return string
     */
    public function serialize(ProviderInterface $provider);
}
