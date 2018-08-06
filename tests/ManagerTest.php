<?php

namespace Tests;

use MilesChou\Toggle\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
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
    public function shouldThrowExceptionWhenCallIsActiveWithNoData()
    {
        $this->setExpectedException('RuntimeException');

        $this->target->isActive('not-exist');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenCallSelectWithNoData()
    {
        $this->setExpectedException('RuntimeException');

        $this->target->select('not-exist');
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCreateFeatureAndReturnTrue()
    {
        $this->target->addFeature('foo', function () {
            return true;
        });

        $this->assertTrue($this->target->isActive('foo'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCreateFeatureAndReturnFalse()
    {
        $this->target->addFeature('foo', function () {
            return false;
        });

        $this->assertFalse($this->target->isActive('foo'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenCreateFeatureAndRemove()
    {
        $this->setExpectedException('RuntimeException', 'foo');

        $this->target->addFeature('foo')
            ->removeFeature('foo');

        $this->target->isActive('foo');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenCreateGroupAndRemove()
    {
        $this->setExpectedException('RuntimeException', 'foo');

        $this->target
            ->addFeature('f1')
            ->addGroup('foo', ['f1'])
            ->removeGroup('foo');

        $this->target->select('foo');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenFeatureIsNotExist()
    {
        $this->setExpectedException('RuntimeException', 'Feature \'not-exist\' is not set');

        $this->target->addGroup('foo', ['not-exist']);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenFeatureHasBeenSetForSomeGroup()
    {
        $this->setExpectedException('RuntimeException', 'Feature has been set for \'foo\'');

        $this->target
            ->addFeature('f1')
            ->addGroup('foo', ['f1']);

        $this->target->addGroup('bar', ['f1']);
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenAddGroupAndReturnFeature1()
    {
        $this->target
            ->addFeature('f1')
            ->addFeature('f2')
            ->addFeature('f3')
            ->addGroup('foo', [
                'f1',
                'f2',
                'f3',
            ], function () {
                return 'f1';
            });

        $this->assertSame('f1', $this->target->select('foo'));

        $this->assertTrue($this->target->isActive('f1'));
        $this->assertFalse($this->target->isActive('f2'));
        $this->assertFalse($this->target->isActive('f3'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenUsingWithGroupAndReturnFeature1()
    {
        $target = $this->target
            ->withFeature('f1')
            ->withFeature('f2')
            ->withFeature('f3')
            ->withGroup('foo', [
                'f1',
                'f2',
                'f3',
            ], function () {
                return 'f1';
            });

        $this->assertSame('f1', $target->select('foo'));

        $this->assertTrue($target->isActive('f1'));
        $this->assertFalse($target->isActive('f2'));
        $this->assertFalse($target->isActive('f3'));
    }
}
