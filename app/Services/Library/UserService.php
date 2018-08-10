<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 09.08.18
 * Time: 17:36
 */

namespace App\Services\Library;


use App\Http\Requests\User\ProfileRequest;
use App\Http\Requests\User\UserCreateForm;
use App\Http\Requests\User\UserEditRequest;
use App\User;
use DB;
use Illuminate\Support\Str;

class UserService
{

    public function create(UserCreateForm $request): User
    {
        return DB::transaction(function () use ($request) {

            /** @var User $user */
            $user = User::make([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('secret'),
                'verify_code' => Str::uuid(),
                'status' => User::STATUS_ACTIVE,
                'role' => $request->role,
                'is_writer' => $request->get('is_writer'),
            ]);

            $user->saveOrFail();

            return $user;

        });
    }

    public function update(UserEditRequest $request, User $user)
    {
        $user->update($request->only('name', 'email', 'role', 'is_writer'));
    }

    public function updateProfile(ProfileRequest $request, User $user)
    {
        if ($request->hasFile('personal_photo') && $request->file('personal_photo')->isValid()) {
            $path = $request->file('personal_photo')->store('avatars', 'public');
            $user->update($request->only('name') + ['personal_photo' => $path]);
        } else {
            $user->update($request->only('name'));
        }
    }

    public function becomeWriter(User $user)
    {
        $user->becomeWriter();
    }

    public function becomeNotWriter(User $user)
    {
        $user->becomeNotWriter();
    }

    public function remove(User $user)
    {
        $user->delete();
    }

}