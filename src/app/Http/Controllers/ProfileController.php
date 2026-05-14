<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load('profile');
        return view('mypage.profile', compact('user'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postcode' => $request->postcode,
                'address'  => $request->address,
                'building' => $request->building,
            ]
        );

        return redirect('/');
    }
}
