<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
    public function toResponse($request)
    {
        // メール認証が完了したらマイページのプロフィールへ飛ばす
        return redirect('/mypage/profile');
    }
}
