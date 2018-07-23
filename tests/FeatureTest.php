<?php

namespace Tests;

use MilesChou\Toggle\Feature;

class FeatureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Feature
     */
    private $target;

    protected function setUp()
    {
        $this->target = new Feature();
    }

    protected function tearDown()
    {
        $this->target = null;
    }

    /**
     * @test
     */
    public function smoke()
    {
        $this->assertInstanceOf(Feature::class, $this->target);
    }
}
