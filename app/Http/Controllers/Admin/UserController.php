<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\User\UserCreateForm;
use App\Http\Requests\User\UserEditRequest;
use App\Services\Library\UserService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class UserController extends Controller
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
        $users = User::orderByDesc('id')->get();

        return view('admin.users.home', compact('users'));
    }

    public function create()
    {
        $roles = User::rolesList();

        return view('admin.users.create', compact('statuses', 'roles'));
    }

    public function store(UserCreateForm $request)
    {
        $user = $this->service->create($request);

        return redirect()->route('admin.users.show', $user)->with('success', 'User successfully created!');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = User::rolesList();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UserEditRequest $request, User $user)
    {
        $this->service->update($request, $user);

        return redirect()->route('admin.users.show', $user)->with('success', 'This user is successfully update');
    }

    public function destroy(User $user)
    {
        $this->service->remove($user);

        return redirect()->route('admin.users.index')->with('success', 'User ' . $user->name . ' is successfully deleted.');
    }
}
