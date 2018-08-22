<?php

namespace Tests\Serializers;

use MilesChou\Toggle\Serializers\YamlSerializer;

class YamlSerializerTest extends \PHPUnit_Framework_TestCase
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
        $excepted = <<< EXCEPTED_DATA
feature:
  f1:
    return: true
  f2:
    return: false
group:
  g1:
    list:
      - f1
      - f2
    return: f1

EXCEPTED_DATA;

        $actual = $this->target->serialize([
            'feature' => [
                'f1' => [
                    'return' => true,
                ],
                'f2' => [
                    'return' => false,
                ],
            ],
            'group' => [
                'g1' => [
                    'list' => [
                        'f1',
                        'f2',
                    ],
                    'return' => 'f1',
                ],
            ],
        ]);

        $this->assertSame($excepted, $actual);
    }

    /**
     * @test
     */
    public function shouldReturnDeserializeResult()
    {
        $input = <<< INPUT_DATA
feature:
  f1:
    return: true
  f2:
    return: false
group:
  g1:
    list:
      - f1
      - f2
    return: f1

INPUT_DATA;

        $exceptedFeature = [
            'f1' => [
                'return' => true,
            ],
            'f2' => [
                'return' => false,
            ],
        ];

        $exceptedGroup = [
            'g1' => [
                'list' => [
                    'f1',
                    'f2',
                ],
                'return' => 'f1',
            ],
        ];

        $actual = $this->target->deserialize($input);

        $this->assertSame($exceptedFeature, $actual['feature']);
        $this->assertSame($exceptedGroup, $actual['group']);
    }
}
