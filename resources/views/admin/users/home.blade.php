@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <h1>Users:</h1>
    <a class="btn btn-success" href=" {{ route('admin.users.create') }}">Create user</a>
    <hr>
    <table class="table table-bordered table-striped">
        <thread>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Role</th>
                <th>Is writer?</th>
                <th>Actions</th>
            </tr>
        </thread>

        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><a href=" {{ route('admin.users.show', $user) }} "><b>{{ $user->name }}</b></a></td>
                <td><b>{{ $user->email }}</b></td>
                @if ($user->isActive())
                    <td><span class="badge badge-primary">{{ $user->status }}</span></td>
                @else
                    <td><span class="badge badge-warning">{{ $user->status }}</span></td>
                @endif
                <td><span class="badge badge-primary">{{ ucfirst($user->role) }}</span></td>
                @if ($user->isWriter())
                    <td><span class="badge badge-primary">Yes</span></td>
                @else
                    <td><span class="badge badge-warning">No</span></td>
                @endif
                <td>
                    <div class="row">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Edit</a>
                        <span>&nbsp&nbsp</span>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
@endsection