<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request, array $guards = [])
    {
        if (!$request->expectsJson()) {

            $route_name = 'login';

            if (is_array($guards) && count($guards) > 0) {
                if ((in_array(User::TYPE_ADMIN, $guards))) {
                    $route_name = 'admin.' . $route_name;
                } elseif ((in_array(User::TYPE_MERCHANT, $guards))) {
                    $route_name = 'merchant.' . $route_name;
                }
            }

            return route($route_name, $request->route()->parameters());
        }
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $this->redirectTo($request, $guards)
        );
    }
}
