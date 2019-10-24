<?php

namespace Tests\Toggle;

use InvalidArgumentException;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Toggle;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class SimplifyToggleTest extends TestCase
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
     * @expectedException RuntimeException
     */
    public function shouldThrowExceptionWhenCallIsActiveWithNoDataAndStrictMode()
    {
        $this->target->setStrict(true);

        $this->target->isActive('not-exist');
    }

    /**
     * @test
     */
    public function shouldReturnFalseDefaultWhenCallIsActive()
    {
        $this->assertFalse($this->target->isActive('not-exist'));
        $this->assertTrue($this->target->isInactive('not-exist'));
    }

    public function invalidProcessor()
    {
        return [
            [123],
            [3.14],
            [''],
            ['str'],
            [new \stdClass()],
        ];
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @dataProvider invalidProcessor
     */
    public function shouldThrowExceptionWithInvalidProcessor($invalidProcessor)
    {
        $this->target->create('foo', $invalidProcessor);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function shouldThrowExceptionWhenGetFeatureAndFeatureNotFound()
    {
        $this->target->feature('not-exist');
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Feature 'foo' is exist
     */
    public function shouldThrowExceptionWhenAddFeatureButFeatureExist()
    {
        $this->target->create('foo');
        $this->target->create('foo');
    }

    /**
     * @test
     */
    public function shouldFlushAllConfigWhenCallFlush()
    {
        $this->target->create('foo');

        $this->assertTrue($this->target->has('foo'));

        $this->target->flush();

        $this->assertFalse($this->target->has('foo'));
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenCreateWithProcessorInsteadOfParam()
    {
        $this->target->create('foo', null, ['some' => 'thing']);

        $this->assertSame('thing', $this->target->params('foo', 'some'));
    }

    /**
     * @test
     */
    public function shouldReturnDefaultWhenCreateWithoutParam()
    {
        $this->target->create('foo', null, ['some' => 'thing']);

        $this->assertSame('default', $this->target->params('foo', 'not-exist', 'default'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCreateFeatureAndReturnTrue()
    {
        $this->target->create('foo', function () {
            return true;
        });

        $this->assertTrue($this->target->isActive('foo'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCreateFeatureAndReturnFalse()
    {
        $this->target->create('foo', function () {
            return false;
        });

        $this->assertFalse($this->target->isActive('foo'));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function shouldThrowExceptionWhenCreateFeatureAndReturnNull()
    {
        $this->target->create('foo', function () {
            return null;
        });

        $this->target->isActive('foo');
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCreateFeatureUsingStaticAndReturnTrue()
    {
        $this->target->create('foo', true);

        $this->assertTrue($this->target->isActive('foo'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCreateFeatureUsingStaticAndReturnFalse()
    {
        $this->target->create('foo', false);

        $this->assertFalse($this->target->isActive('foo'));
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage foo
     */
    public function shouldThrowExceptionWhenCreateFeatureAndRemoveFeature()
    {
        $this->target->setStrict(true);

        $this->target->create('foo')
            ->remove('foo');

        $this->target->isActive('foo');
    }

    /**
     * @test
     */
    public function shouldReturnSameResultWhenIsActiveWithDifferentContext()
    {
        $this->target->create('f1', function ($context) {
            return 0 === $context['id'] % 2;
        });

        $this->assertTrue($this->target->isActive('f1', ['id' => 2]));
        $this->assertTrue($this->target->isActive('f1', ['id' => 3]));
    }

    /**
     * @test
     */
    public function shouldReturnDifferentResultWhenCallIsActiveAfterDuplicateWithPreserveParameter()
    {
        $this->target->create('f1', function ($context) {
            return 0 === $context['id'] % 2;
        });

        $this->assertTrue($this->target->isActive('f1', ['id' => 2]));

        $this->assertFalse($this->target->duplicate(false)->isActive('f1', ['id' => 3]));
        $this->assertTrue($this->target->duplicate(true)->isActive('f1', ['id' => 3]));
    }

    /**
     * @test
     */
    public function shouldReturnStaticResultWhenCreateFeatureUsingStatic()
    {
        $this->target->create('foo', null, [], false);

        $this->target->result([
            'foo' => true,
        ]);

        $this->assertFalse($this->target->isActive('foo'));
    }

    /**
     * @test
     */
    public function shouldReturnResultResultWhenGetTheResult()
    {
        $actual = $this->target
            ->create('f1', true)
            ->create('f2', false)
            ->result();

        $this->assertSame(['f1' => true, 'f2' => false], $actual);
    }

    /**
     * @test
     */
    public function shouldReturnResultResultWhenGetTheResultWithSomeExistPreserveResult()
    {
        $this->target
            ->create('f1', true)
            ->create('f2', false);

        $this->target->isActive('f1');

        $actual = $this->target->result();

        $this->assertSame(['f1' => true, 'f2' => false], $actual);
    }

    /**
     * @test
     */
    public function shouldReturnResultResultWhenPutTheResult()
    {
        $this->target
            ->create('f1')
            ->create('f2')
            ->result(['f1' => true, 'f2' => false]);

        $this->assertTrue($this->target->isActive('f1'));
        $this->assertFalse($this->target->isActive('f2'));
    }

    /**
     * @test
     */
    public function shouldBeWorkWhenCallParams()
    {
        $this->target->create('f1', null, [
            'foo' => 'a',
        ]);

        $this->assertSame(['foo' => 'a'], $this->target->params('f1'));
        $this->assertSame('a', $this->target->params('f1', 'foo'));

        $this->target->params('f1', ['bar' => 'b']);

        $this->assertSame(['foo' => 'a', 'bar' => 'b'], $this->target->params('f1'));
        $this->assertSame('a', $this->target->params('f1', 'foo'));
        $this->assertSame('b', $this->target->params('f1', 'bar'));
    }

    /**
     * @test
     */
    public function shouldBeWorkWhenCallProcessor()
    {
        $this->target->create('f1', null, [
            'foo' => 'a',
        ]);

        $this->assertInternalType('callable', $this->target->processor('f1'));
    }

    /**
     * @covers \MilesChou\Toggle\Toggle::when
     * @test
     */
    public function shouldBeWorkWhenCallWhen()
    {
        $this->target->create('f1', true, ['bar' => 'b']);

        $this->target->when('f1', function (Feature $feature, $context) {
            $this->assertSame('a', $context['foo']);
            $this->assertSame('b', $feature->params('bar'));
        }, null, ['foo' => 'a']);
    }

    /**
     * @covers \MilesChou\Toggle\Toggle::when
     * @test
     */
    public function shouldBeWorkWhenCallWhenWithDefault()
    {
        $this->target->create('f1', false, ['bar' => 'b']);

        $this->target->when(
            'f1',
            function () {
            },
            function () {
                $this->assertTrue(true);
            },
            ['foo' => 'a']
        );
    }

    /**
     * @covers \MilesChou\Toggle\Toggle::when
     * @test
     */
    public function shouldBeWorkWhenCallWhenWithoutDefault()
    {
        $this->target->create('f1', false, ['bar' => 'b']);

        $actual = $this->target->when('f1', function () {
        });

        $this->assertInstanceOf(Toggle::class, $actual);
    }

    /**
     * @covers \MilesChou\Toggle\Toggle::unless
     * @test
     */
    public function shouldBeWorkWhenCallUnless()
    {
        $this->target->create('f1', false, ['bar' => 'b']);

        $this->target->unless('f1', function (Feature $feature, $context) {
            $this->assertSame('a', $context['foo']);
            $this->assertSame('b', $feature->params('bar'));
        }, null, ['foo' => 'a']);
    }

    /**
     * @covers \MilesChou\Toggle\Toggle::unless
     * @test
     */
    public function shouldBeWorkWhenCallUnlessWithDefault()
    {
        $this->target->create('f1', true, ['bar' => 'b']);

        $this->target->unless(
            'f1',
            function () {
            },
            function () {
                $this->assertTrue(true);
            },
            ['foo' => 'a']
        );
    }

    /**
     * @covers \MilesChou\Toggle\Toggle::unless
     * @test
     */
    public function shouldBeWorkWhenCallUnlessWithoutDefault()
    {
        $this->target->create('f1', true, ['bar' => 'b']);

        $actual = $this->target->unless('f1', function () {
        });

        $this->assertInstanceOf(Toggle::class, $actual);
    }
}
