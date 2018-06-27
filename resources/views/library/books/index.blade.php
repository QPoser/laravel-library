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
        <p>No books.</p>
    @endif
    @if(!$bundles->isEmpty())
        <br>
        <h2>Bundles</h2>
        <hr>
        @foreach($bundles as $bundle)
            <strong><a href="{{ route('library.bundles.show', $bundle) }}">{{ $bundle->title }}</a></strong>
        @endforeach
    @endif
@endsection