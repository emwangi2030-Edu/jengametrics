<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    protected $proxies;

    protected function getProxyHeader(Request $request)
    {
        return $request::HEADER_X_FORWARDED_FOR;
    }
}
