<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function create(Item $item)
    {
        return view('purchases.create', compact('item'));
    }
}
