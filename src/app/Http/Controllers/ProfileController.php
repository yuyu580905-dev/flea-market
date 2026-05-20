<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load('profile');
        return view('mypage.profile', compact('user'));
    }
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->update([
            'name' => $request->name,
        ]);
        $profileData = [
            'postcode' => $request->postcode,
            'address'  => $request->address,
            'building' => $request->building,
        ];

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $profileData['profile_image'] = basename($path);
        }

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect('/');
    }
}
