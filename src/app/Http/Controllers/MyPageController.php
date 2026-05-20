<?php

namespace App\Http\Controllers;

use App\Models\Item;

class MyPageController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('profile');
        $page = request('page');

        if ($page === 'buy') {

            $items = Item::whereHas('purchase', function ($query) use ($user) {

                $query->where('user_id', $user->id);
            })->get();
        } else {

            $items = $user->items;
        }

        return view('mypage', compact('items', 'user'));
    }
}
