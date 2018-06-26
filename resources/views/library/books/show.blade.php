@extends('layouts.app')

@section('content')
    <h1>{{ $book->title }}</h1>
    <p>Genre: {{ $book->genre->name }}</p>
    <p>Author: {{ $book->author->name }}</p>
    <p>{{ $book->description }}</p>
    <a href="{{ asset('storage/' . $book->file_path) }}" class="btn btn-success" download>Download</a>
@endsection