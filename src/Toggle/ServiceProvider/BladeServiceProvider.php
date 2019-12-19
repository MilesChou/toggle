<?php

namespace MilesChou\Toggle\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\EngineResolver;
use MilesChou\Toggle\Toggle;

/**
 * Provider to register Blade's directive
 */
class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /** @var EngineResolver $view */
        $viewEngine = $this->app->get('view.engine.resolver');

        /** @var BladeCompiler $blade */
        $blade = $viewEngine->resolve('blade')->getCompiler();

        /** @var Toggle $toggle */
        $toggle = $this->app->get(Toggle::class);

        $blade->directive('isActive', function ($feature, $context = []) use ($toggle) {
            $condition = $toggle->isActive($feature, $context) ? 'true' : 'false';

            return "<?php if ({$condition}): ?>";
        });

        $blade->directive('isInactive', function ($feature, $context = []) use ($toggle) {
            $condition = $toggle->isInactive($feature, $context) ? 'true' : 'false';

            return "<?php if ({$condition}): ?>";
        });
    }
}
