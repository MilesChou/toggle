<?php

namespace Tests\Serializers;

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
        $actual = $this->target->serialize([
            'f' => [
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
            'g' => [
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

        $actual = $this->target->deserialize('{"f":{"f1":{"r":true},"f2":{"r":false},"f3":{"r":false}},"g":{"g1":{"l":["f1","f2","f3"],"r":"f1"}}}');

        $this->assertSame($exceptedFeature, $actual['f']);
        $this->assertSame($exceptedGroup, $actual['g']);
    }
}
