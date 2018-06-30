@extends('layouts.app')

@section('content')
    @include('admin.genres._nav')

    <h1>{{ $genre->name }}</h1>
    <h2>Id: {{ $genre->id }}</h2>
    <h3>Status: {{ $genre->status }}</h3>

    <div class="row">
        @if ($genre->isActive())
            <form action="{{ route('admin.genres.set-inactive', $genre) }}" method="POST">
                @csrf
                <button class="btn btn-warning" type="submit">Set inactive</button>
            </form>
        @else
            <form action="{{ route('admin.genres.set-active', $genre) }}" method="POST">
                @csrf
                <button class="btn btn-success" type="submit">Set active</button>
            </form>
        @endif

        <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST">
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

        @foreach($genre->books as $book)
            <tr>
                <td>{{ $book->id }}</td>
                <td><a href=" {{ route('admin.books.show', $book) }} "><b>{{ $book->title }}</b></a></td>
            </tr>
    @endforeach
@endsection