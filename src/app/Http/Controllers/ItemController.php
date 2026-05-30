<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;

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
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('sell', compact('categories', 'conditions'));
    }
    public function store(ExhibitionRequest $request)
    {
        $imagePath = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'user_id' => Auth::id(),
            'condition_id' => $request->condition_id,
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'is_sold' => false,
        ]);

        $item->categories()->attach($request->categories);

        return redirect('/');
    }
}
