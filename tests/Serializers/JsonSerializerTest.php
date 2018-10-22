<?php

namespace Tests\Serializers;

use MilesChou\Toggle\Providers\ArrayProvider;
use MilesChou\Toggle\Serializers\JsonSerializer;

class JsonSerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonSerializer
     */
    private $target;

    protected function setUp()
    {
        $this->target = new JsonSerializer();
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
        $provider = new ArrayProvider([
            'feature' => [
                'f1' => [
                    'r' => true,
                ],
                'f2' => [
                    'r' => false,
                ],
                'f3' => [
                    'r' => false,
                ],
            ],
            'group' => [
                'g1' => [
                    'l' => [
                        'f1',
                        'f2',
                        'f3',
                    ],
                    'r' => 'f1',
                ],
            ],
        ]);

        $actual = $this->target->serialize($provider);

        $this->assertSame(
            '{"feature":{"f1":{"r":true},"f2":{"r":false},"f3":{"r":false}},"group":{"g1":{"l":["f1","f2","f3"],"r":"f1"}}}',
            $actual
        );
    }

    /**
     * @test
     */
    public function shouldReturnDeserializeResult()
    {
        $expectedFeature = [
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

        $expectedGroup = [
            'g1' => [
                'l' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'r' => 'f1',
            ],
        ];

        $actual = $this->target->deserialize('{"feature":{"f1":{"r":true},"f2":{"r":false},"f3":{"r":false}},"group":{"g1":{"l":["f1","f2","f3"],"r":"f1"}}}', new ArrayProvider());

        $this->assertSame($expectedFeature, $actual->getFeatures());
        $this->assertSame($expectedGroup, $actual->getGroups());
    }
}
