<?php

namespace Tests\Providers;

use MilesChou\Toggle\Providers\ArrayProvider;

class ArrayProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayProvider
     */
    private $target;

    protected function setUp()
    {
        $this->target = new ArrayProvider();
    }

    protected function tearDown()
    {
        $this->target = null;
    }

    /**
     * @test
     */
    public function shouldReturnSerializeResult()
    {
        $this->target->setFeatures([
            'f1' => true,
            'f2' => false,
            'f3' => false,
        ]);

        $actual = $this->target->serialize();

        $this->assertSame('{"features":{"f1":true,"f2":false,"f3":false},"groups":[]}', $actual);
    }

    /**
     * @test
     */
    public function shouldUnserializeOk()
    {
        $excepted = [
            'f1' => true,
            'f2' => false,
            'f3' => false,
        ];

        $actual = $this->target->unserialize('{"features":{"f1":true,"f2":false,"f3":false},"groups":[]}');

        $this->assertSame($excepted, $actual->getFeatures());
    }
}
