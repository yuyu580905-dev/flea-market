<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // ログイン状態であるかチェック
            if (Auth::guard($guard)->check()) {

                // 1. ログイン済み ＆ メール未認証の場合、メール認証待ち画面へ
                if ($request->user($guard) && ! $request->user($guard)->hasVerifiedEmail()) {
                    return redirect('/email/verify');
                }

                // 2. ログイン済み ＆ 認証完了済みの場合は、本来のトップ画面（/）へ
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
