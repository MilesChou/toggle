<?php

namespace Tests;

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Contracts\DataProviderInterface;
use MilesChou\Toggle\DataProvider;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Manager;

class PersistenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Manager
     */
    private $target;

    protected function setUp()
    {
        $this->target = new Manager();
    }

    protected function tearDown()
    {
        $this->target = null;
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenExportToUnknownClass()
    {
        $this->setExpectedException('RuntimeException', 'Unknown class');

        $this->target->export(null, 'Unknown');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenExportToClassNotExtendsProvider()
    {
        $this->setExpectedException('RuntimeException', 'Driver must instance of Provider');

        $this->target->export(null, 'stdClass');
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenImportFeatureOnly()
    {
        $dataProvider = new DataProvider([
            'f1' => [
                'params' => [],
                'return' => true,
            ],
            'f2' => [
                'params' => [],
                'return' => false,
            ],
            'f3' => [
                'params' => [],
                'return' => false,
            ],
        ]);

        $this->target->import($dataProvider);

        $this->assertTrue($this->target->isActive('f1'));
        $this->assertFalse($this->target->isActive('f2'));
        $this->assertFalse($this->target->isActive('f3'));
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenExportFeatureOnly()
    {
        $expected = [
            'f1' => [
                'params' => [],
                'return' => true,
            ],
            'f2' => [
                'params' => [],
                'return' => false,
            ],
            'f3' => [
                'params' => [],
                'return' => false,
            ],
        ];

        $this->target
            ->createFeature('f1', true)
            ->createFeature('f2', false)
            ->createFeature('f3', false);

        $actual = $this->target->export();

        $this->assertInstanceOf('MilesChou\\Toggle\\Contracts\\DataProviderInterface', $actual);
        $this->assertSame($expected, $actual->getFeatures());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenExportWithContext()
    {
        $expected = [
            'f1' => [
                'params' => [],
                'return' => false,
            ],
        ];

        $this->target
            ->createFeature('f1', function (Context $context) {
                return $context->return;
            });

        $actual = $this->target->export(Context::create(['return' => false]));

        $this->assertInstanceOf('MilesChou\\Toggle\\Contracts\\DataProviderInterface', $actual);
        $this->assertSame($expected, $actual->getFeatures());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenImportFeatureAndGroup()
    {
        $dataProvider = new DataProvider([
            'f1' => [
                'params' => [],
                'return' => true,
            ],
            'f2' => [
                'params' => [],
                'return' => false,
            ],
            'f3' => [
                'params' => [],
                'return' => false,
            ],
        ], [
            'g1' => [
                'params' => [],
                'list' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'return' => 'f1',
            ],
        ]);

        $this->target->import($dataProvider);

        $this->assertSame('f1', $this->target->select('g1'));

        $this->assertTrue($this->target->isActive('f1'));
        $this->assertFalse($this->target->isActive('f2'));
        $this->assertFalse($this->target->isActive('f3'));
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenExportFeatureAndGroup()
    {
        $expectedFeature = [
            'f1' => [
                'params' => [],
                'return' => true,
            ],
            'f2' => [
                'params' => [],
                'return' => false,
            ],
            'f3' => [
                'params' => [],
                'return' => false,
            ],
        ];

        $expectedGroup = [
            'g1' => [
                'params' => [],
                'list' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'return' => 'f1',
            ],
        ];

        $this->target
            ->addFeature(Feature::create('f1', true))
            ->addFeature(Feature::create('f2', false))
            ->addFeature(Feature::create('f3', false))
            ->createGroup('g1', ['f1', 'f2', 'f3'], 'f1');

        $actual = $this->target->export();

        $this->assertInstanceOf('MilesChou\\Toggle\\Contracts\\DataProviderInterface', $actual);
        $this->assertSame($expectedFeature, $actual->getFeatures());
        $this->assertSame($expectedGroup, $actual->getGroups());
    }

    /**
     * @test
     */
    public function shouldReturnImportResultWhenInitAndExportFeatureAndGroup()
    {
        $this->target
            ->createFeature('f1')
            ->createFeature('f2')
            ->createFeature('f3')
            ->createGroup('g1', ['f1', 'f2', 'f3'], 'f1')
            ->select('g1');

        $dataProvider = new DataProvider([
            'f1' => [
                'params' => [],
                'return' => true,
            ],
            'f2' => [
                'params' => [],
                'return' => false,
            ],
            'f3' => [
                'params' => [],
                'return' => false,
            ],
        ], [
            'g1' => [
                'params' => [],
                'list' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'return' => 'f3',
            ],
        ]);

        $this->target->import($dataProvider);

        $this->assertSame('f3', $this->target->select('g1'));

        $this->assertFalse($this->target->isActive('f1'));
        $this->assertFalse($this->target->isActive('f2'));
        $this->assertTrue($this->target->isActive('f3'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenInitAndExportFeatureAndGroupWithoutClean()
    {
        $this->setExpectedException('RuntimeException', "Feature 'f1' is exist");

        $this->target
            ->createFeature('f1')
            ->createFeature('f2')
            ->createFeature('f3')
            ->createGroup('g1', ['f1', 'f2', 'f3'], 'f1')
            ->select('g1');

        $dataProvider = new DataProvider([
            'f1' => [
                'params' => [],
                'return' => true,
            ],
            'f2' => [
                'params' => [],
                'return' => false,
            ],
            'f3' => [
                'params' => [],
                'return' => false,
            ],
        ], [
            'g1' => [
                'params' => [],
                'list' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'return' => 'f3',
            ],
        ]);

        $this->target->import($dataProvider, false);
    }
}
