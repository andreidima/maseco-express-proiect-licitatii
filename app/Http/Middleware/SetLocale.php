<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SESSION_KEY = 'locale';
    private const COOKIE_KEY = 'locale';
    private const SUPPORTED = ['ro', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        if ($request->user()) {
            $locale = $request->session()->get(self::SESSION_KEY);
        }

        if (!$locale) {
            $cookieLocale = $request->cookie(self::COOKIE_KEY);
            if ($request->user()) {
                if (is_string($cookieLocale) && in_array($cookieLocale, self::SUPPORTED, true)) {
                    $request->session()->put(self::SESSION_KEY, $cookieLocale);
                    Cookie::queue(Cookie::forget(self::COOKIE_KEY));
                    $locale = $cookieLocale;
                }
            } else {
                $locale = $cookieLocale;
            }
        }

        if (!$locale) {
            $locale = $request->session()->get(self::SESSION_KEY);
        }

        if (!is_string($locale) || !in_array($locale, self::SUPPORTED, true)) {
            $locale = config('app.locale', 'ro');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
