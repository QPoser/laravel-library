<?php

namespace App\Http\Controllers\Cabinet;

use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        ]);

        $user = Auth::user();

        $user->update($request->only('name'));

        return redirect()->route('cabinet.profile.home');
    }
}
