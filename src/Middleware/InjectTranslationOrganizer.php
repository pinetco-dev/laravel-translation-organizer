<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Middleware;

use Closure;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Pinetcodev\LaravelTranslationOrganizer\Services\Manager;
use Throwable;

class InjectTranslationOrganizer
{

    /**
     * @var Manager $manager ;
     */
    protected Manager $manager;

    /**
     * Create a new middleware instance.
     *
     * @param Container $container
     * @param Manager $manager
     */
    public function __construct(Container $container, Manager $manager)
    {
        $this->container = $container;
        $this->manager = $manager;
        //$this->except = config('debugbar.except') ?: [];
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // here check if translation inject is enable.

        if (!$this->manager->isEnable()) {
            return $next($request);
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
     * @param $passable
     * @param Throwable $e
     * @return mixed
     * @throws Exception
     */
    protected function handleException($passable, $e)
    {
        if (!$this->container->bound(ExceptionHandler::class) || !$passable instanceof Request) {
            throw $e;
        }

        $handler = $this->container->make(ExceptionHandler::class);

        $handler->report($e);

        return $handler->render($passable, $e);
    }
}
