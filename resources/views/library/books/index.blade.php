@extends('layouts.app')

@section('content')
    <h1>Books</h1>
    <hr>
    @if(!$books->isEmpty())
        @foreach($books as $book)
            <strong><a href="{{ route('library.books.show', $book) }}">{{ $book->title }}</a></strong>
            <p>Genre: {{ $book->genre->name }}</p>
            <p>Author: {{ $book->author->name }}</p>
            <p>User: {{ $book->user->name }}</p>
            <hr>
        @endforeach
    @else
        2
    @endif
@endsection