<?php

namespace Tests\Serializers;

use MilesChou\Toggle\Serializers\JsonDataProvider;

class JsonSerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonDataProvider
     */
    private $target;

    protected function setUp()
    {
        $this->target = new JsonDataProvider();
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
            'f1' => [
                'r' => true,
            ],
            'f2' => [
                'r' => false,
            ],
            'f3' => [
                'r' => false,
            ],
        ]);

        $this->target->setGroups([
            'g1' => [
                'l' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'r' => 'f1',
            ],
        ]);

        $actual = $this->target->serialize();

        $this->assertSame('{"f":{"f1":{"r":true},"f2":{"r":false},"f3":{"r":false}},"g":{"g1":{"l":["f1","f2","f3"],"r":"f1"}}}', $actual);
    }

    /**
     * @test
     */
    public function shouldUnserializeOk()
    {
        $exceptedFeature = [
            'f1' => [
                'r' => true,
            ],
            'f2' => [
                'r' => false,
            ],
            'f3' => [
                'r' => false,
            ],
        ];

        $exceptedGroup = [
            'g1' => [
                'l' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'r' => 'f1',
            ],
        ];

        $actual = $this->target->unserialize('{"f":{"f1":{"r":true},"f2":{"r":false},"f3":{"r":false}},"g":{"g1":{"l":["f1","f2","f3"],"r":"f1"}}}');

        $this->assertSame($exceptedFeature, $actual->getFeatures());
        $this->assertSame($exceptedGroup, $actual->getGroups());
    }
}
