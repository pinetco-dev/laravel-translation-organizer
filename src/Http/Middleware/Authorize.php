<?php

namespace Outhebox\LaravelTranslations\Http\Middleware;

class Authorize
{
    public function handle($request, $next)
    {
        return Laraveltranslation - organizer::check($request) ? $next($request) : abort(403);
    }
}
