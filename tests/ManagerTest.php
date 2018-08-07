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
        $this->target->createFeature('foo', function () {
            return true;
        });

        $this->assertTrue($this->target->isActive('foo'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCreateFeatureAndReturnFalse()
    {
        $this->target->createFeature('foo', function () {
            return false;
        });

        $this->assertFalse($this->target->isActive('foo'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenCreateFeatureAndRemoveFeature()
    {
        $this->setExpectedException('RuntimeException', 'foo');

        $this->target->createFeature('foo')
            ->removeFeature('foo');

        $this->target->isActive('foo');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenCreateGroupAndRemove()
    {
        $this->setExpectedException('RuntimeException', 'g1');

        $this->target
            ->createFeature('f1')
            ->createGroup('g1', ['f1'])
            ->removeGroup('g1');

        $this->target->select('g1');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenFeatureIsNotExist()
    {
        $this->setExpectedException('RuntimeException', 'Feature \'not-exist\' is not set');

        $this->target->createGroup('g1', ['not-exist']);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenFeatureHasBeenSetForSomeGroup()
    {
        $this->setExpectedException('RuntimeException', 'Feature has been set for \'g1\'');

        $this->target
            ->createFeature('f1')
            ->createGroup('g1', ['f1']);

        $this->target->createGroup('bar', ['f1']);
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenAddGroupAndReturnFeature1()
    {
        $this->target
            ->createFeature('f1')
            ->createFeature('f2')
            ->createFeature('f3')
            ->createGroup('foo', [
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
}
