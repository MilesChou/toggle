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
        $this->target->setFeatures([
            'f1' => [
                'result' => true,
            ],
            'f2' => [
                'result' => false,
            ],
            'f3' => [
                'result' => false,
            ],
        ]);

        $this->target->setGroups([
            'g1' => [
                'list' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'result' => 'f1',
            ],
        ]);

        $actual = $this->target->serialize();

        $this->assertSame('{"features":{"f1":{"result":true},"f2":{"result":false},"f3":{"result":false}},"groups":{"g1":{"list":["f1","f2","f3"],"result":"f1"}}}', $actual);
    }

    /**
     * @test
     */
    public function shouldUnserializeOk()
    {
        $exceptedFeature = [
            'f1' => [
                'result' => true,
            ],
            'f2' => [
                'result' => false,
            ],
            'f3' => [
                'result' => false,
            ],
        ];

        $exceptedGroup = [
            'g1' => [
                'list' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'result' => 'f1',
            ],
        ];

        $actual = $this->target->unserialize('{"features":{"f1":{"result":true},"f2":{"result":false},"f3":{"result":false}},"groups":{"g1":{"list":["f1","f2","f3"],"result":"f1"}}}');

        $this->assertSame($exceptedFeature, $actual->getFeatures());
        $this->assertSame($exceptedGroup, $actual->getGroups());
    }
}
