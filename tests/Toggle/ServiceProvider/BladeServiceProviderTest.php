<?php

namespace Tests\Toggle\ServiceProvider;

use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Fluent;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\ViewServiceProvider;
use MilesChou\Toggle\ServiceProvider\BladeServiceProvider;
use MilesChou\Toggle\Toggle;

class BladeServiceProviderTest extends TestCase
{
    public function createApplication()
    {
        $app = new Application();
        $app->register(EventServiceProvider::class);
        $app->register(FilesystemServiceProvider::class);
        $app->register(ViewServiceProvider::class);

        $app->register(BladeServiceProvider::class);

        $app->singleton(Toggle::class);

        $app->instance('config', new Fluent([
            'view.paths' => ['/tmp'],
            'view.compiled' => '/tmp',
        ]));

        Facade::setFacadeApplication($app);

        return $app;
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenUsingIsActiveWithToggleIsNotSet()
    {
        $this->app->boot();

        /** @var EngineResolver $view */
        $view = $this->app->make('view.engine.resolver');

        /** @var BladeCompiler $blade */
        $blade = $view->resolve('blade')->getCompiler();

        $actual = $blade->compileString("@isActive('feature')");

        $this->assertSame('<?php if (false): ?>', $actual);
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenUsingIsInactiveWithToggleIsNotSet()
    {
        $this->app->boot();

        /** @var EngineResolver $view */
        $view = $this->app->make('view.engine.resolver');

        /** @var BladeCompiler $blade */
        $blade = $view->resolve('blade')->getCompiler();

        $actual = $blade->compileString("@isInactive('feature')");

        $this->assertSame('<?php if (true): ?>', $actual);
    }
}
