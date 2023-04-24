<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Middleware;

use Closure;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Pinetcodev\LaravelTranslationOrganizer\Services\Manager;
use Pinetcodev\LaravelTranslationOrganizer\TranslationServiceProvider;
use Throwable;

class InjectTranslationOrganizer
{
    /**
     * @var Manager ;
     */
    protected Manager $manager;

    /**
     * Create a new middleware instance.
     */
    public function __construct(Container $container, Manager $manager)
    {
        $this->container = $container;
        $this->manager = $manager;
    }

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // here check if translation inject is enable.

        if ($this->manager->isEnable()) {
            app()->register(TranslationServiceProvider::class);
        }

        // check if translation is not enable
        try {
            $response = $next($request);
        } catch (Throwable $e) {
            $response = $this->handleException($request, $e);
        }

        $this->manager->modifyResponse($request, $response);

        return $response;
    }

    /**
     * Handle the given exception.
     *
     * (Copy from Illuminate\Routing\Pipeline by Taylor Otwell)
     *
     * @param  Throwable  $e
     * @return mixed
     *
     * @throws Exception
     */
    protected function handleException($passable, $e)
    {
        if (! $this->container->bound(ExceptionHandler::class) || ! $passable instanceof Request) {
            throw $e;
        }

        $handler = $this->container->make(ExceptionHandler::class);

        $handler->report($e);

        return $handler->render($passable, $e);
    }
}
