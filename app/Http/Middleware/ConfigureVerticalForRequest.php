<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class ConfigureVerticalForRequest
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = parse_url($request->url(), PHP_URL_HOST);
        match ($host) {
            'account.protestant.test' => $this->configure('protestant'),
            'account.catholic.test' => $this->configure('catholic'),
            default => throw new \RuntimeException('Unknown host found: '. $host)
        };

        return $next($request);
    }

    protected function configure(string $vertical): void
    {
        App::setLocale("en-{$vertical}");
        config()->set('app.name', trans('vertical.name'));
    }
}
