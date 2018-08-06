<?php

namespace Tests\Concerns;

class GroupTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldBeOkWhenAddAnEmptyFeatureGroup()
    {
        $target = $this->getMockForTrait('MilesChou\Toggle\Concerns\GroupTrait');
        $target->addGroup('foo', []);
    }
}
