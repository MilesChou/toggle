<?php

namespace Tests;

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

        $this->target->export('Unknown');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenExportToClassNotExtendsProvider()
    {
        $this->setExpectedException('RuntimeException', 'Driver must instance of Provider');

        $this->target->export('stdClass');
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenImportFeatureOnly()
    {
        $dataProvider = new DataProvider([
            'f1' => [
                'r' => true,
            ],
            'f2' => [
                'r' => false,
            ],
            'f3' => [
                'r' => false,
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
        $excepted = [
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

        $this->target
            ->addFeature('f1', Feature::create()->setProcessedResult(true))
            ->addFeature('f2', Feature::create()->setProcessedResult(false))
            ->addFeature('f3', Feature::create()->setProcessedResult(false));

        $actual = $this->target->export();

        $this->assertInstanceOf('MilesChou\Toggle\Contracts\DataProviderInterface', $actual);
        $this->assertSame($excepted, $actual->getFeatures());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenImportFeatureAndGroup()
    {
        $dataProvider = new DataProvider([
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

        $this->target
            ->addFeature('f1', Feature::create()->setProcessedResult(true))
            ->addFeature('f2', Feature::create()->setProcessedResult(false))
            ->addFeature('f3', Feature::create()->setProcessedResult(false))
            ->createGroup('g1', ['f1', 'f2', 'f3'], 'f1');

        $actual = $this->target->export();

        $this->assertInstanceOf('MilesChou\Toggle\Contracts\DataProviderInterface', $actual);
        $this->assertSame($exceptedFeature, $actual->getFeatures());
        $this->assertSame($exceptedGroup, $actual->getGroups());
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
            ->createGroup('g1', [
                'f1',
                'f2',
                'f3',
            ], 'f1')
            ->select('g1');

        $dataProvider = new DataProvider([
            'f1' => [
                'r' => false,
            ],
            'f2' => [
                'r' => false,
            ],
            'f3' => [
                'r' => true,
            ],
        ], [
            'g1' => [
                'l' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'r' => 'f3',
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
        $this->setExpectedException('RuntimeException', 'Feature has been set');

        $this->target
            ->createFeature('f1')
            ->createFeature('f2')
            ->createFeature('f3')
            ->createGroup('g1', [
                'f1',
                'f2',
                'f3',
            ], 'f1')
            ->select('g1');

        $dataProvider = new DataProvider([
            'f1' => [
                'r' => false,
            ],
            'f2' => [
                'r' => false,
            ],
            'f3' => [
                'r' => true,
            ],
        ], [
            'g1' => [
                'l' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'r' => 'f3',
            ],
        ]);

        $this->target->import($dataProvider, false);
    }
}
