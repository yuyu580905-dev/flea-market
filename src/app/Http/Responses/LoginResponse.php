<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // ログインしたユーザーがまだメール認証を済ませていない場合
        if (! $request->user()->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }

        // 認証済みの場合はトップ（またはHOME）へ
        return redirect()->intended(config('fortify.home'));
    }
}
