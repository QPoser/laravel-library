<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Requests\User\ProfileRequest;
use App\Services\Library\UserService;
use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * @var UserService
     */
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

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

    public function update(ProfileRequest $request)
    {
        $this->service->updateProfile($request, Auth::user());

        return redirect()->route('cabinet.profile.home');
    }

    public function becomeWriter()
    {
        try {
            $this->service->becomeWriter(Auth::user());
        } catch (\DomainException $e) {
            return redirect()->route('cabinet.profile.home')->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.profile.home')->with('success', 'Now you\'re a writer');
    }

    public function becomeNotWriter()
    {
        try {
            $this->service->becomeNotWriter(Auth::user());
        } catch (\DomainException $e) {
            return redirect()->route('cabinet.profile.home')->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.profile.home')->with('success', 'Now you\'re not a writer');
    }
}
