<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        // 会員登録直後に認証待ち画面（/email/verify）へ飛ばす
        return redirect('/email/verify');
    }
}
