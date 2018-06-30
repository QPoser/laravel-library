@extends('layouts.app')

@section('content')
    @include('admin.books._nav')

    <h1>{{ $book->title }}</h1>
    <h2>Id: {{ $book->id }}</h2>
    <h3>Status: {{ $book->status }}</h3>
    <h3>Author: {{ $book->author->name }}</h3>
    <h3>Genre: {{ $book->genre->name }}</h3>
    <h3>User: {{ $book->user->name }}</h3>
    <p>{{ $book->description }}</p>

    <div class="row">
        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-primary">Edit</a>

        <form action="{{ route('admin.books.destroy', $book) }}" method="POST">
            @csrf
            @method('DELETE')

            <button class="btn btn-danger" type="submit">Delete</button>
        </form>
    </div>
@endsection