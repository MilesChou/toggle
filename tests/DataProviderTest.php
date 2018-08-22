<?php

namespace Tests;

use MilesChou\Toggle\Contracts\DataProviderInterface;
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
            'f1' => [
                DataProviderInterface::FEATURE_RETURN => true,
            ],
            'f2' => [
                DataProviderInterface::FEATURE_RETURN => false,
            ],
            'f3' => [
                DataProviderInterface::FEATURE_RETURN => false,
            ],
        ], [
            'g1' => [
                DataProviderInterface::GROUP_LIST => [
                    'f1',
                    'f2',
                    'f3',
                ],
                DataProviderInterface::GROUP_RETURN => 'f1',
            ],
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
                DataProviderInterface::FEATURE_RETURN => true,
            ],
            'f2' => [
                DataProviderInterface::FEATURE_RETURN => false,
            ],
            'f3' => [
                DataProviderInterface::FEATURE_RETURN => false,
            ],
        ];

        $expectedGroup = [
            'g1' => [
                DataProviderInterface::GROUP_LIST => [
                    'f1',
                    'f2',
                    'f3',
                ],
                DataProviderInterface::GROUP_RETURN => 'f1',
            ],
        ];

        $input = '{"feature":{"f1":{"return":true},"f2":{"return":false},"f3":{"return":false}},"group":{"g1":{"list":["f1","f2","f3"],"return":"f1"}}}';

        $target = new DataProvider();

        $actual = $target->deserialize($input);

        $this->assertInstanceOf('MilesChou\\Toggle\\DataProvider', $actual);
        $this->assertSame($expectedFeature, $actual->getFeatures());
        $this->assertSame($expectedGroup, $actual->getGroups());
    }
}
