@extends('layouts.app')

@section('content')
    <h1>{{ $bundle->title }}</h1>
    @foreach($bundle->books as $book)
        <p><a href="{{ route('library.books.show', $book) }}">{{ $book->title }}</a></p>
    @endforeach
@endsection