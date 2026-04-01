<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Per-request CSP nonce: injects nonce on all script and style open tags in HTML,
 * then sets Content-Security-Policy with matching script-src/style-src nonces.
 */
class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('api/*')) {
            return $next($request);
        }

        $rawNonce = $this->generateNonce();
        app()->instance('csp.nonce', $rawNonce);

        $response = $next($request);

        $contentType = (string) $response->headers->get('Content-Type', '');
        if ($contentType === '' || ! str_contains($contentType, 'text/html')) {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || $content === '') {
            $this->applyPolicyHeader($response, $rawNonce);

            return $response;
        }

        $attrNonce = htmlspecialchars($rawNonce, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $response->setContent($this->injectNonces($content, $attrNonce));
        $this->applyPolicyHeader($response, $rawNonce);

        return $response;
    }

    private function generateNonce(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(16)), '+/', '-_'), '=');
    }

    /**
     * @param  non-empty-string  $escapedAttrNonce
     */
    private function injectNonces(string $html, string $escapedAttrNonce): string
    {
        $out = preg_replace_callback(
            '/<(script|style)\b(?![^>]*\bnonce\s*=)([^>]*)>/i',
            static fn (array $m): string => '<'.$m[1].' nonce="'.$escapedAttrNonce.'"'.$m[2].'>',
            $html
        );

        return is_string($out) ? $out : $html;
    }

    private function applyPolicyHeader(Response $response, string $rawNonce): void
    {
        $origin = rtrim((string) config('app.url'), '/');
        $n = $rawNonce;

        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "frame-ancestors 'self'",
            "form-action 'self'",
            "font-src 'self' https://fonts.gstatic.com data:",
            // unsafe-inline: JS libraries often set element style attributes at runtime.
            "style-src 'self' 'nonce-{$n}' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
            "script-src 'self' 'nonce-{$n}' https://cdn.jsdelivr.net https://code.jquery.com https://maps.googleapis.com",
            "img-src 'self' data: blob: https:",
            "connect-src 'self' {$origin} https://cdn.jsdelivr.net https://*.tile.openstreetmap.org https://api.mapbox.com https://events.mapbox.com",
            'upgrade-insecure-requests',
        ];

        $response->headers->set('Content-Security-Policy', implode('; ', $directives));
    }
}
