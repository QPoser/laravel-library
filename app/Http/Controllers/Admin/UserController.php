<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
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

    public function store(Request $request)
    {
        $roles = User::rolesList();

        $this->validate($request, [
            'name' => 'required|string|max:32|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => ['required', 'string', Rule::in($roles)],
            'is_writer' => 'nullable',
        ]);

        $user = User::new($request->name, $request->email, $request->role, $request->has('is_writer'));

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

    public function update(Request $request, User $user)
    {
        $roles = User::rolesList();

        $this->validate($request, [
            'name' => 'required|string|max:32|unique:users,id,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,id,' . $user->id,
            'role' => ['required', 'string', Rule::in($roles)],
            'is_writer' => 'nullable',
        ]);

        $user->update($request->only('name', 'email', 'role') + ['is_writer' => $request->has('is_writer')]);

        return redirect()->route('admin.users.show', $user)->with('success', 'This user is successfully update');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User ' . $user->name . ' is successfully deleted.');
    }
}
