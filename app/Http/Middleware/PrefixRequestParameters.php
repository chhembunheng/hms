<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PrefixRequestParameters
{
    /**
     * Handle an incoming request.
     * Extracts filters from the HTTP_FILTERS header (set by DataTables ajax beforeSend, matching beltei_ums)
     * and merges them into the Request parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $parameters = [];
        $rawFilters = $_SERVER['HTTP_FILTERS'] ?? $request->header('filters');

        if ($rawFilters) {
            $filters = json_decode(urldecode($rawFilters), true);
            if (is_array($filters)) {
                foreach ($filters as $key => $value) {
                    if (in_array($key, ['search', 'search_text'])) {
                        $parameters[$key] = is_string($value) ? trim($value) : $value;
                    } else {
                        $parameters[$key] = $value;
                    }
                }
                $parameters = array_filter($parameters, fn($v) => $v !== null && $v !== '');
                $request->merge($parameters);
            }
        }

        return $next($request);
    }
}
