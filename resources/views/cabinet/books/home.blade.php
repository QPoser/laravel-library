@extends('layouts.app')

@section('content')
    @include('cabinet.books._nav')

    <h1>{{ ucfirst($user->name) }} books:</h1>
    <a class="btn btn-success" href=" {{ route('cabinet.books.create') }}">Create book</a>
    <hr>
    @forelse ($books as $book)
        <p><a href="{{ route('cabinet.books.show', $book) }}">{{ $book->title }}</a></p>
        <hr>
    @empty
        <p>You have not books.</p>
    @endforelse

@endsection