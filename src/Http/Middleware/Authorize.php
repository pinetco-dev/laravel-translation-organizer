<?php

namespace Outhebox\LaravelTranslations\Http\Middleware;

class Authorize
{
    public function handle($request, $next)
    {
        $next($request);
    }
}
