<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Group;

trait GroupTrait
{
    /**
     * @var Group[]
     */
    private $group = [];

    /**
     * @param string $name
     * @param Group $group
     */
    public function addGroup($name, Group $group)
    {
        $this->group[$name] = $group;
    }

    /**
     * @param string $name
     */
    public function removeGroup($name)
    {
        unset($this->group[$name]);
    }
}
