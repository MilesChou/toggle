<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\DataProviderTrait;
use MilesChou\Toggle\Concerns\SerializerAwareTrait;
use MilesChou\Toggle\Contracts\DataProviderInterface;

class DataProvider implements DataProviderInterface
{
    use DataProviderTrait;
    use SerializerAwareTrait;

    /**
     * @param array $features
     * @param array $groups
     */
    public function __construct(array $features = [], array $groups = [])
    {
        $this->setFeatures($features);
        $this->setGroups($groups);
    }

    /**
     * @param mixed $data
     * @return static
     */
    protected function retrieveRestoreData($data)
    {
        $this->setFeatures($data['f']);
        $this->setGroups($data['g']);

        return $this;
    }

    /**
     * @return mixed
     */
    protected function retrieveStoreData()
    {
        return [
            'f' => $this->getFeatures(),
            'g' => $this->getGroups(),
        ];
    }
}
