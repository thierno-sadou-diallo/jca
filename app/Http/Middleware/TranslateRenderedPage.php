<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslateRenderedPage
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (app()->getLocale() !== 'en' || ! $this->shouldTranslate($request, $response)) {
            return $response;
        }

        $content = $response->getContent();

        if (! is_string($content) || $content === '') {
            return $response;
        }

        $dictionary = trans('page_text');

        if (! is_array($dictionary)) {
            return $response;
        }

        uksort($dictionary, fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        $response->setContent(str_replace(array_keys($dictionary), array_values($dictionary), $content));

        return $response;
    }

    private function shouldTranslate(Request $request, Response $response): bool
    {
        if ($request->is('admin*')) {
            return false;
        }

        return $response->headers->get('content-type') === null
            || str_contains((string) $response->headers->get('content-type'), 'text/html');
    }
}
