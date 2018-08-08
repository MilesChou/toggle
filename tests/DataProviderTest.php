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
        $target = new DataProvider([
            'f1' => [
                'r' => true,
            ],
            'f2' => [
                'r' => false,
            ],
            'f3' => [
                'r' => false,
            ],
        ], [
            'g1' => [
                'l' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'r' => 'f1',
            ],
        ]);

        $this->assertSame('{"f":{"f1":{"r":true},"f2":{"r":false},"f3":{"r":false}},"g":{"g1":{"l":["f1","f2","f3"],"r":"f1"}}}', $target->serialize());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenUnerializeDataToDataProvider()
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

        $target = new DataProvider();

        $actual = $target->unserialize('{"f":{"f1":{"r":true},"f2":{"r":false},"f3":{"r":false}},"g":{"g1":{"l":["f1","f2","f3"],"r":"f1"}}}');

        $this->assertInstanceOf('MilesChou\\Toggle\\DataProvider', $actual);
        $this->assertSame($exceptedFeature, $actual->getFeatures());
        $this->assertSame($exceptedGroup, $actual->getGroups());
    }
}
