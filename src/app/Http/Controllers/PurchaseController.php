<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    public function create(Item $item)
    {
        $profile = auth()->user()->profile;

        $address = session('purchase_address', [
            'postcode' => $profile?->postcode ?? '',
            'address' => $profile?->address ?? '',
            'building' => $profile?->building ?? '',
        ]);

        return view('purchases.create', compact('item', 'address'));
    }
    public function editAddress(Item $item)
    {
        $profile = auth()->user()->profile;

        $address = session('purchase_address', [
            'postcode' => $profile?->postcode ?? '',
            'address' => $profile?->address ?? '',
            'building' => $profile?->building ?? '',
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
    public function checkout(PurchaseRequest $request, Item $item)
    {
        if ($item->is_sold) {
            return redirect('/');
        }

        session([
            'purchase_data' => [
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building,
                'payment_method' => $request->payment_method,
            ]
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],

            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],

            'mode' => 'payment',
            'success_url' => route('purchase.success', $item),
            'cancel_url' => route('purchase.cancel', $item),
        ]);

        return redirect($session->url);
    }
    public function success(Item $item)
    {
        if ($item->is_sold) {
            return redirect('/');
        }

        $data = session('purchase_data');

        $user = auth()->user();

        if (!$user->profile) {
            $user->profile()->create([
                'postcode' => $data['postcode'],
                'address' => $data['address'],
                'building' => $data['building'],
            ]);
        }

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'postcode' => $data['postcode'],
            'address' => $data['address'],
            'building' => $data['building'],
            'payment_method' => $data['payment_method'],
        ]);

        $item->update([
            'is_sold' => true,
        ]);

        session()->forget('purchase_address');
        session()->forget('purchase_data');

        return redirect('/');
    }
    public function cancel(Item $item)
    {
        return redirect("/purchase/{$item->id}");
    }
}
