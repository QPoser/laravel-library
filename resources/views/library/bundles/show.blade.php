@extends('layouts.app')

@section('content')
    <h1>{{ $bundle->title }}</h1>
    <p>User: <a href="{{ route('library.users.show', $bundle->user) }}">{{ $bundle->user->name }}</a></p>
    <hr>
    @foreach($bundle->books as $book)
        <p><a href="{{ route('library.books.show', $book) }}">{{ $book->title }}</a></p>
    @endforeach
@endsection