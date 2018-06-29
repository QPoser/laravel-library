<?php

namespace App\Http\Controllers\Cabinet;

use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('cabinet.profile.home', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('cabinet.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'personal_photo' => 'mimes:jpeg,png',
        ]);

        $user = Auth::user();

        if ($request->hasFile('personal_photo') && $request->file('personal_photo')->isValid()) {
            $path = $request->file('personal_photo')->store('avatars', 'public');
            $user->update($request->only('name') + ['personal_photo' => $path]);
        } else {
            $user->update($request->only('name'));
        }

        return redirect()->route('cabinet.profile.home');
    }

    public function becomeWriter()
    {
        $user = Auth::user();

        try {
            $user->becomeWriter();
        } catch (\DomainException $e) {
            return redirect()->route('cabinet.profile.home')->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.profile.home')->with('success', 'Now you\'re a writer');
    }

    public function becomeNotWriter()
    {
        $user = Auth::user();

        try {
            $user->becomeNotWriter();
        } catch (\DomainException $e) {
            return redirect()->route('cabinet.profile.home')->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.profile.home')->with('success', 'Now you\'re not a writer');
    }
}
