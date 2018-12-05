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
            ]
        ]);

        $this->assertSame(
            '{"feature":{"f1":{"r":true},"f2":{"r":false},"f3":{"r":false}}}',
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

        $actual = $this->target->deserialize('{"f1":{"r":true},"f2":{"r":false},"f3":{"r":false}}');

        $this->assertSame($expectedFeature, $actual);
    }
}
