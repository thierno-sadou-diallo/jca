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

        $response->setContent($this->translateTextSegments($content, $dictionary));

        return $response;
    }

    /**
     * @param  array<string, string>  $dictionary
     */
    private function translateTextSegments(string $content, array $dictionary): string
    {
        $segments = preg_split('/(<[^>]+>)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE);

        if (! is_array($segments)) {
            return $content;
        }

        foreach ($segments as $index => $segment) {
            if ($segment === '' || str_starts_with($segment, '<')) {
                continue;
            }

            $segments[$index] = str_replace(array_keys($dictionary), array_values($dictionary), $segment);
        }

        return implode('', $segments);
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
