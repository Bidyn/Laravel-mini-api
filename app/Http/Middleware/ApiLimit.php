<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Auth;

class ApiLimit extends ThrottleRequests
{
    CONST MINUTE = 'minute';
    CONST DAY = 'day';
    CONST DECAY_MINUTE = 1;
    CONST DECAY_DAY = 1440;
    private $maxAttemptsPerMinute = 10;
    private $maxAttemptsPerDay = 1000;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $maxAttempts = 10, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key . self::DAY, $this->maxAttemptsPerDay)) {
            return $this->buildResponse($key, $this->maxAttemptsPerDay);
        } elseif ($this->limiter->tooManyAttempts($key . self::MINUTE, $this->maxAttemptsPerMinute)) {
            return $this->buildResponse($key, $this->maxAttemptsPerMinute);
        }

        $this->limiter->hit($key . self::DAY, self::DECAY_DAY);
        $this->limiter->hit($key . self::MINUTE, self::DECAY_MINUTE);

        $response = $next($request);


        return $this->addRateLimitingHeadersHeaders($response, $key);
    }

    protected function resolveRequestSignature($request)
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $this->maxAttemptsPerMinute = 100;
            $this->maxAttemptsPerDay = 10000;
            return $this->getFingerprintForUser($request, $user);
        }

        return $request->fingerprint();
    }

    /**
     * @param $request
     * @param $user
     * @return string
     */
    protected function getFingerprintForUser($request, $user): string
    {
        if (!$route = $request->route()) {
            throw new \RuntimeException('Unable to generate fingerprint. Route unavailable.');
        }
        return sha1(implode('|', array_merge(
            $route->methods(), [$route->domain(), $route->uri(), $user->id]
        )));
    }


    protected function buildResponse($key, $maxAttempts)
    {
        $response = new Response('Too Many Attempts.', 429);

        $retryAfter = $this->limiter->availableIn($key);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param int $key
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    protected function addRateLimitingHeadersHeaders($response, $key)
    {
        $remainingAttemptsPerDay = $this
            ->calculateRemainingAttempts(
                $key . self::DAY,
                $this->maxAttemptsPerDay
            );
        $remainingAttemptsPerMinute = $this
            ->calculateRemainingAttempts(
                $key . self::MINUTE,
                $this->maxAttemptsPerMinute
            );
        if ($remainingAttemptsPerDay < $remainingAttemptsPerMinute){

            return parent::addHeaders(
                $response, $this->maxAttemptsPerDay,
                $this->calculateRemainingAttempts($key . self::DAY, $this->maxAttemptsPerDay)

            );
        }

        return parent::addHeaders(
            $response, $this->maxAttemptsPerMinute,
            $this->calculateRemainingAttempts($key . self::MINUTE, $this->maxAttemptsPerMinute)
        );
    }
}
