<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function create(Item $item)
    {
        $profile = auth()->user()->profile;

        $address = session('purchase_address', [
            'postcode' => $profile->postcode,
            'address' => $profile->address,
            'building' => $profile->building,
        ]);

        return view('purchases.create', compact('item', 'address'));
    }
    public function editAddress(Item $item)
    {
        $profile = auth()->user()->profile;

        $address = session('purchase_address', [
            'postcode' => $profile->postcode,
            'address' => $profile->address,
            'building' => $profile->building,
        ]);

        return view('purchases.address', compact('item', 'address'));
    }
    public function updateAddress(AddressRequest $request, Item $item)
    {
        session([
            'purchase_address' => [
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);

        return redirect("/purchase/{$item->id}");
    }
    public function store(PurchaseRequest $request, Item $item)
    {
        if ($item->is_sold) {
            return redirect('/');
        }

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,

            'postcode' => $request->postcode,
            'address' => $request->address,
            'building' => $request->building,

            'payment_method' => $request->payment_method,
        ]);

        $item->update([
            'is_sold' => true,
        ]);

        session()->forget('purchase_address');

        return redirect('/');
    }
}
