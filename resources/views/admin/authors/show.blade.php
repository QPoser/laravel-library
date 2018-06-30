@extends('layouts.app')

@section('content')
    @include('admin.authors._nav')

    <h1>{{ $author->name }}</h1>
    <h2>Id: {{ $author->id }}</h2>
    <h3>Status: {{ $author->status }}</h3>

    <div class="row">
        @if ($author->isActive())
            <form action="{{ route('admin.authors.set-inactive', $author) }}" method="POST">
                @csrf
                <button class="btn btn-warning" type="submit">Set inactive</button>
            </form>
        @else
            <form action="{{ route('admin.authors.set-active', $author) }}" method="POST">
                @csrf
                <button class="btn btn-success" type="submit">Set active</button>
            </form>
        @endif

        <form action="{{ route('admin.authors.destroy', $author) }}" method="POST">
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

        @foreach($author->books as $book)
            <tr>
                <td>{{ $book->id }}</td>
                <td><a href=" {{ route('admin.books.show', $book) }} "><b>{{ $book->title }}</b></a></td>
            </tr>
    @endforeach
@endsection