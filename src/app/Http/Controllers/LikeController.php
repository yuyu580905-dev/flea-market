<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class LikeController extends Controller
{
    public function toggle(Item $item)
    {
        $user = Auth::user();

        if ($item->isLikedBy($user)) {

            // いいね解除
            $item->likedUsers()->detach($user->id);
        } else {

            // いいね追加
            $item->likedUsers()->attach($user->id);
        }

        return back();
    }
}
