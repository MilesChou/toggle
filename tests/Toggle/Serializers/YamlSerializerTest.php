<?php

namespace Tests\Toggle\Serializers;

use MilesChou\Toggle\Serializers\YamlSerializer;
use PHPUnit\Framework\TestCase;

class YamlSerializerTest extends TestCase
{
    /**
     * @var YamlSerializer
     */
    private $target;

    protected function setUp()
    {
        $this->target = new YamlSerializer();
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
        $expected = <<< EXCEPTED_DATA
feature:
  f1:
    return: true
  f2:
    return: false

EXCEPTED_DATA;

        $actual = $this->target->serialize([
            'feature' => [
                'f1' => [
                    'return' => true,
                ],
                'f2' => [
                    'return' => false,
                ],
            ]
        ]);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function shouldReturnDeserializeResult()
    {
        $input = <<< INPUT_DATA
f1:
  return: true
f2:
  return: false

INPUT_DATA;

        $expectedFeature = [
            'f1' => [
                'return' => true,
            ],
            'f2' => [
                'return' => false,
            ],
        ];

        $actual = $this->target->deserialize($input);

        $this->assertSame($expectedFeature, $actual);
    }
}
