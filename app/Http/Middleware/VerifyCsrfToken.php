<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/communities/create','/posts/create','/posts/delete/*',
        '/posts/update/*','communities/update/*','communities/delete/*',
        'communities/*/members/*','comments/delete/*','comments/add',
        'likes/delete/*','likes/add'
    ];
}
