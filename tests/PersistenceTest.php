<?php

namespace Tests;

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Manager;
use MilesChou\Toggle\Serializers\JsonSerializer;

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
        $dataProvider = new JsonSerializer([
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
                'result' => true,
            ],
            'f2' => [
                'result' => false,
            ],
            'f3' => [
                'result' => false,
            ],
        ];

        $this->target
            ->addFeature('f1', Feature::create()->setProcessedResult(true))
            ->addFeature('f2', Feature::create()->setProcessedResult(false))
            ->addFeature('f3', Feature::create()->setProcessedResult(false));

        $actual = $this->target->export('MilesChou\Toggle\Serializers\JsonSerializer');

        $this->assertSame($excepted, $actual->getFeatures());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenImportFeatureAndGroup()
    {
        $dataProvider = new JsonSerializer([
            'f1' => [
                'result' => true,
            ],
            'f2' => [
                'result' => false,
            ],
            'f3' => [
                'result' => false,
            ],
        ], [
            'g1' => [
                'list' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'result' => 'f1',
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

        $this->target
            ->addFeature('f1', Feature::create()->setProcessedResult(true))
            ->addFeature('f2', Feature::create()->setProcessedResult(false))
            ->addFeature('f3', Feature::create()->setProcessedResult(false))
            ->createGroup('g1', ['f1', 'f2', 'f3'], 'f1');

        $actual = $this->target->export('MilesChou\Toggle\Serializers\JsonSerializer');

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

        $dataProvider = new JsonSerializer([
            'f1' => [
                'result' => false,
            ],
            'f2' => [
                'result' => false,
            ],
            'f3' => [
                'result' => true,
            ],
        ], [
            'g1' => [
                'list' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'result' => 'f3',
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

        $dataProvider = new JsonSerializer([
            'f1' => [
                'result' => false,
            ],
            'f2' => [
                'result' => false,
            ],
            'f3' => [
                'result' => true,
            ],
        ], [
            'g1' => [
                'list' => [
                    'f1',
                    'f2',
                    'f3',
                ],
                'result' => 'f3',
            ],
        ]);

        $this->target->import($dataProvider, false);
    }
}
