@extends('layouts.app')

@section('content')
    @include('cabinet.books._nav')

    <h1>{{ $book->title }}</h1>
    <p>{{ $book->description }}</p>
    <a href="{{ asset('storage/' . $book->file_path) }}" class="btn btn-success" download>Download</a>
    <a href="{{ route('cabinet.books.edit', $book) }}" class="btn btn-primary">Edit</a>
    <br>
    <br>
    <form action="{{ route('cabinet.books.remove', $book) }}" method="POST">
        @csrf

        <button class="btn btn-danger" type="submit">Delete</button>
    </form>
@endsection