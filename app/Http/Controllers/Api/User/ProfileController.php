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

    /**
     * @SWG\Get(
     *     path="/user",
     *     tags={"Profile"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Profile"),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function show(Request $request)
    {
        return new ProfileResource($request->user());
    }

    /**
     * @SWG\Put(
     *     path="/user",
     *     tags={"Profile"},
     *     @SWG\Parameter(name="body", in="body", required=true, @SWG\Schema(ref="#/definitions/ProfileEditRequest")),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function update(ProfileRequest $request)
    {
        $this->service->updateProfile($request, $request->user());

        return User::findOrFail($request->user()->id);
    }
}
