<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\User\ProfileRequest;
use App\Http\Resources\User\ProfileResource;
use App\Services\Library\UserService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function show(Request $request)
    {
        return new ProfileResource($request->user());
    }

    public function update(ProfileRequest $request)
    {
        $this->service->updateProfile($request, $request->user());

        return User::findOrFail($request->user()->id);
    }
}
