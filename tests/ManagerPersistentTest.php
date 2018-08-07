<?php

namespace Tests;

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Manager;
use MilesChou\Toggle\Providers\ArrayProvider;

class ManagerPersistentTest extends \PHPUnit_Framework_TestCase
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
    public function shouldReturnCorrectResultWhenImportFeatureOnly()
    {
        $dataProvider = new ArrayProvider([
            'f1' => true,
            'f2' => false,
            'f3' => false,
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
            'f1' => true,
            'f2' => false,
            'f3' => false,
        ];

        $this->target
            ->addFeature('f1', Feature::create()->setProcessedResult(true))
            ->addFeature('f2', Feature::create()->setProcessedResult(false))
            ->addFeature('f3', Feature::create()->setProcessedResult(false));

        $actual = $this->target->export('MilesChou\Toggle\Providers\ArrayProvider');

        $this->assertSame($excepted, $actual->getFeatures());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenImportFeatureAndGroup()
    {
        $dataProvider = new ArrayProvider([
            'f1' => true,
            'f2' => false,
            'f3' => false,
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
            'f1' => true,
            'f2' => false,
            'f3' => false,
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
            ->addGroup('g1', ['f1', 'f2', 'f3'], 'f1');

        $actual = $this->target->export('MilesChou\Toggle\Providers\ArrayProvider');

        $this->assertSame($exceptedFeature, $actual->getFeatures());
        $this->assertSame($exceptedGroup, $actual->getGroups());
    }
}
