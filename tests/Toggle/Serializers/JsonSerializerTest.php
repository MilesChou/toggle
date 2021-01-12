<?php

namespace Tests\Toggle\Serializers;

use MilesChou\Toggle\Serializers\JsonSerializer;
use PHPUnit\Framework\TestCase;

class JsonSerializerTest extends TestCase
{
    /**
     * @var JsonSerializer
     */
    private $target;

    protected function setUp(): void
    {
        $this->target = new JsonSerializer();
    }

    protected function tearDown(): void
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
