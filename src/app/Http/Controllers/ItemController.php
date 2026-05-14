<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab');
        $keyword = trim($request->input('keyword'));

        if ($tab === 'mylist') {

            if (!Auth::check()) {
                $items = collect();
            } else {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $query = $user
                    ->likes()
                    ->where('items.user_id', '!=', Auth::id());

                if ($keyword) {
                    $query->where('items.name', 'like', '%' . $keyword . '%');
                }
                $items = $query->get();
            }
        } else {
            $query = Item::query();

            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }

            if ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }

            $items = $query->get();
        }

        return view('items.index', compact('items'));
    }
    public function show(Item $item)
    {
        $item->load([
            'categories',
            'comments.user',
            'comments.user.profile',
            'condition',
            'likedUsers',
        ]);

        return view('items.show', compact('item'));
    }
}
