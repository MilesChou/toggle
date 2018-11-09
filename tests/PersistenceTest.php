<?php

namespace Tests;

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Contracts\ProviderInterface;
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
            ]
        ]);

        $this->target->import($dataProvider);

        $this->assertTrue($this->target->isActive('f1'));
        $this->assertFalse($this->target->isActive('f2'));
        $this->assertFalse($this->target->isActive('f3'));
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
}
