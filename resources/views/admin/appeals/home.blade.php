@extends('layouts.app')

@section('content')
    @include('admin.appeals._nav')

    <h1>Users:</h1>
    <a class="btn btn-success" href=" {{ route('admin.users.create') }}">Create user</a>
    <hr>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Book</th>
                <th>User</th>
                <th>Status</th>
                <th>Reason</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($appeals as $appeal)
                <tr>
                    <td>{{ $appeal->id }}</td>
                    <td><a href="{{ route('library.books.show', $appeal->book) }}">{{ $appeal->book->title }}</a></td>
                    <td><a href="{{ route('library.users.show', $appeal->user) }}">{{ $appeal->user->name }}</a></td>
                    <td>{{ $appeal->status }}</td>
                    <td>{{ $appeal->reason }}</td>
                    @if ($appeal->isAccepted())
                        <td>
                            <form action="{{ route('admin.appeals.cancel', $appeal) }}" method="POST">
                                @csrf

                                <button class="btn btn-warning" type="submit">Cancel</button>
                            </form>
                        </td>
                    @elseif ($appeal->isCanceled())
                        <td>
                            <form action="{{ route('admin.appeals.accept', $appeal) }}" method="POST">
                                @csrf

                                <button class="btn btn-success" type="submit">Accept</button>
                            </form>
                        </td>
                    @else
                        <td>
                            <form action="{{ route('admin.appeals.accept', $appeal) }}" method="POST">
                                @csrf

                                <button class="btn btn-success" type="submit">Accept</button>
                            </form>

                            <form action="{{ route('admin.appeals.cancel', $appeal) }}" method="POST">
                                @csrf

                                <button class="btn btn-warning" type="submit">Cancel</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection