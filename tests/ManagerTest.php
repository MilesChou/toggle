<?php

namespace Tests;

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;
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
    public function shouldReturnTrueWhenCreateFeatureUsingStaticAndReturnTrue()
    {
        $this->target->createFeature('foo', true);

        $this->assertTrue($this->target->isActive('foo'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCreateFeatureUsingStaticAndReturnFalse()
    {
        $this->target->createFeature('foo', false);

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
    public function shouldThrowExceptionWhenCreateGroupWithoutFeature()
    {
        $this->setExpectedException('RuntimeException', 'Some feature is not exist');

        $this->target->createGroup('g1', ['not-exist']);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenAddGroupWithoutFeature()
    {
        $this->setExpectedException('RuntimeException', 'Some feature is exist');

        $this->target
            ->createFeature('exist')
            ->addGroup('g1', Group::create(['exist' => Feature::create()]));
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
    public function shouldReturnSameResultWhenIsActiveWithDifferentContext()
    {
        $this->target->createFeature('f1', function (Context $context) {
            $id = $context->getParam('id');

            return 0 === $id % 2;
        });

        $this->assertTrue($this->target->isActive('f1', Context::create(['id' => 2])));
        $this->assertTrue($this->target->isActive('f1', Context::create(['id' => 3])));
    }

    /**
     * @test
     */
    public function shouldReturnSameResultWhenSelectWithDifferentContext()
    {
        $this->target
            ->createFeature('f1')
            ->createFeature('f2')
            ->createGroup('g1', ['f1', 'f2'], function (Context $context) {
                $id = $context->getParam('id');

                if (0 === $id % 2) {
                    return 'f1';
                } else {
                    return 'f2';
                }
            });

        $this->assertSame('f1', $this->target->select('g1', Context::create(['id' => 2])));
        $this->assertSame('f1', $this->target->select('g1', Context::create(['id' => 3])));
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenAddGroup()
    {
        $this->target->addGroup('g1', Group::create([
            'f1' => Feature::create(),
            'f2' => Feature::create(),
            'f3' => Feature::create(),
        ], 'f1'));

        $this->assertSame('f1', $this->target->select('g1'));

        $this->assertTrue($this->target->isActive('f1'));
        $this->assertFalse($this->target->isActive('f2'));
        $this->assertFalse($this->target->isActive('f3'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCreateGroupAndReturnFeature1()
    {
        $this->target
            ->createFeature('f1')
            ->createFeature('f2')
            ->createFeature('f3')
            ->createGroup('g1', ['f1', 'f2', 'f3'], 'f1');

        $this->assertSame('f1', $this->target->select('g1'));

        $this->assertTrue($this->target->isActive('f1'));
        $this->assertFalse($this->target->isActive('f2'));
        $this->assertFalse($this->target->isActive('f3'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCreateGroupAndUsingContextAndReturnFeature1()
    {
        $context = Context::create();
        $context->return = 'f1';

        $this->target
            ->createFeature('f1')
            ->createFeature('f2')
            ->createFeature('f3')
            ->createGroup('g1', [
                'f1',
                'f2',
                'f3',
            ], function (Context $context) {
                return $context->return;
            });

        $this->assertSame('f1', $this->target->select('g1', $context));

        $this->assertTrue($this->target->isActive('f1'));
        $this->assertFalse($this->target->isActive('f2'));
        $this->assertFalse($this->target->isActive('f3'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCreateGroupAndUsingContextSetterAndReturnFeature1()
    {
        $context = Context::create();
        $context->return = 'f1';

        $this->target
            ->setContext($context)
            ->createFeature('f1')
            ->createFeature('f2')
            ->createFeature('f3')
            ->createGroup('g1', [
                'f1',
                'f2',
                'f3',
            ], function (Context $context) {
                return $context->return;
            });

        $this->assertSame('f1', $this->target->select('g1'));

        $this->assertTrue($this->target->isActive('f1'));
        $this->assertFalse($this->target->isActive('f2'));
        $this->assertFalse($this->target->isActive('f3'));
    }

    /**
     * @test
     */
    public function shouldReturnF2WhenContextSetterF1AndMethodInjectF2()
    {
        $context = Context::create(['return' => 'f1']);

        $this->target
            ->setContext($context)
            ->createFeature('f1')
            ->createFeature('f2')
            ->createFeature('f3')
            ->createGroup('g1', [
                'f1',
                'f2',
                'f3',
            ], function (Context $context) {
                return $context->return;
            });

        $this->assertSame('f2', $this->target->select('g1', Context::create(['return' => 'f2'])));

        $this->assertFalse($this->target->isActive('f1'));
        $this->assertTrue($this->target->isActive('f2'));
        $this->assertFalse($this->target->isActive('f3'));
    }
}
