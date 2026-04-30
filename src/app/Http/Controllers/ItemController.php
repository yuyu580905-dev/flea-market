<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }
    public function show(Item $item)
    {
        $item->load([
            'categories',
            'comments.user',
            'condition',
        ]);

        return view('items.show', compact('item'));
    }
}
