@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <h1>{{ $user->name }}</h1>
    <h2>Id: {{ $user->id }}</h2>
    <h3>Status: {{ $user->status }}</h3>

    <div class="row">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Edit</a>

        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
            @csrf
            @method('DELETE')

            <button class="btn btn-danger" type="submit">Delete</button>
        </form>
    </div>
    <hr>
    <h3>Books:</h3>
    <table class="table table-bordered table-striped">
        <thread>
            <tr>
                <th>ID</th>
                <th>Title</th>
            </tr>
        </thread>

        @foreach($user->books as $book)
            <tr>
                <td>{{ $book->id }}</td>
                <td><a href=" {{ route('admin.books.show', $book) }} "><b>{{ $book->title }}</b></a></td>
            </tr>
        @endforeach
    </table>
    <hr>
    <h3>Bundles:</h3>
    <table class="table table-bordered table-striped">
        <thread>
            <tr>
                <th>ID</th>
                <th>Title</th>
            </tr>
        </thread>

        @foreach($user->bundles as $bundle)
            <tr>
                <td>{{ $bundle->id }}</td>
                <td><a href=" {{ route('admin.bundles.show', $bundle) }} "><b>{{ $bundle->title }}</b></a></td>
            </tr>
        @endforeach
    </table>
@endsection