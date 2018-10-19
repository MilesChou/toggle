<?php

namespace Tests;

use MilesChou\Toggle\DataProvider;

class DataProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenSerializeDataProvider()
    {
        $expected = '{"feature":{"f1":{"return":true},"f2":{"return":false},"f3":{"return":false}},"group":{"g1":{"list":["f1","f2","f3"],"return":"f1"}}}';

        $target = new DataProvider([
            'feature' => [
                'f1' => [
                    'return' => true,
                ],
                'f2' => [
                    'return' => false,
                ],
                'f3' => [
                    'return' => false,
                ],
            ],
            'group' => [
                'g1' => [
                    'list' => [
                        'f1',
                        'f2',
                        'f3',
                    ],
                    'return' => 'f1',
                ],
            ]
        ]);

        $this->assertSame($expected, $target->serialize());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenDeserializeDataToDataProvider()
    {
        $expectedFeature = [
            'f1' => [
                'return' => true,
            ],
            'f2' => [
                'return' => false,
            ],
            'f3' => [
                'return' => false,
            ],
        ];

        $expectedGroup = [
            'g1' => [
                'list' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'return' => 'f1',
            ],
        ];

        $input = '{"feature":{"f1":{"return":true},"f2":{"return":false},"f3":{"return":false}},"group":{"g1":{"list":["f1","f2","f3"],"return":"f1"}}}';

        $target = new DataProvider();

        $actual = $target->deserialize($input);

        $this->assertInstanceOf(DataProvider::class, $actual);
        $this->assertSame($expectedFeature, $actual->getFeatures());
        $this->assertSame($expectedGroup, $actual->getGroups());
    }
}
