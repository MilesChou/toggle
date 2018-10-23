<?php

namespace Tests;

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Contracts\ProviderInterface;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Providers\DataProvider;
use MilesChou\Toggle\Providers\ResultProvider;
use MilesChou\Toggle\Toggle;

class PersistenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Toggle
     */
    private $target;

    protected function setUp()
    {
        $this->target = new Toggle();
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
        $dataProvider = new DataProvider([
            'feature' => [
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

        $this->assertInstanceOf(ProviderInterface::class, $actual);
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

        $this->assertInstanceOf(ProviderInterface::class, $actual);
        $this->assertSame($expected, $actual->getFeatures());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenImportFeatureAndGroup()
    {
        $dataProvider = new DataProvider([
            'feature' => [
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
            ],
            'group' => [
                'g1' => [
                    'params' => [],
                    'list' => [
                        'f1',
                        'f2',
                        'f3',
                    ],
                    'return' => 'f1',
                ],
            ]
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

        $this->assertInstanceOf(ProviderInterface::class, $actual);
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
            'feature' => [
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
            ],
            'group' => [
                'g1' => [
                    'params' => [],
                    'list' => [
                        'f1',
                        'f2',
                        'f3',
                    ],
                    'return' => 'f3',
                ],
            ]
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
            'feature' =>
                [
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
                ],
            'group' => [
                'g1' => [
                    'params' => [],
                    'list' => [
                        'f1',
                        'f2',
                        'f3',
                    ],
                    'return' => 'f3',
                ],
            ]
        ]);

        $this->target->import($dataProvider, false);
    }

    /**
     * @test
     */
    public function shouldReturnStaticResultWhenCreateFeatureUsingStatic()
    {
        $this->target->createFeature('foo');
        $this->target->feature('foo')->staticResult(false);

        $data = ResultProvider::create()
            ->feature('foo', true);

        $this->target->result($data);

        $this->assertFalse($this->target->isActive('foo'));
    }
    /**
     * @test
     */
    public function shouldReturnStaticResultWhenCreateGroupUsingStatic()
    {
        $this->target
            ->createFeature('f1')
            ->createFeature('f2')
            ->createGroup('foo', ['f1', 'f2']);
        $this->target->group('foo')->staticResult('f1');

        $data = ResultProvider::create()
            ->group('foo', 'f2');

        $this->target->result($data);

        $this->assertSame('f1', $this->target->select('foo'));
    }
}
