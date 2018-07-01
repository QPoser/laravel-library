@extends('layouts.app')

@section('breadcrumbs')

@endsection

@section('content')
    <h1>Books</h1>
    <hr>
    <form action="{{ route('library.books.home') }}">
        @csrf

        <div class="form-group">
            <label for="genre" class="col-form-label">Genre</label>
            <select name="genre" id="genre">
                <option value=""></option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ $genre->id == request('genre') ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('genre'))
                <span class="invalid-feedback"><strong>{{ $errors->first('genre') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="author" class="col-form-label">Author</label>
            <select name="author" id="author">
                <option value=""></option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" {{ $author->id == request('author') ? 'selected' : '' }}>{{ $author->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('author'))
                <span class="invalid-feedback"><strong>{{ $errors->first('author') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="search" class="col-form-label">Search</label>
            <input id="search" class="form-control{{ $errors->has('search') ? ' is-invalid' : '' }}" name="search" value="{{ request('search') }}">
            @if ($errors->has('search'))
                <span class="invalid-feedback"><strong>{{ $errors->first('search') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">
                Search
            </button>
        </div>
    </form>
    <hr>
    @if(!$books->isEmpty())
        @foreach($books as $book)
            <strong><a href="{{ route('library.books.show', $book) }}">{{ $book->title }}</a></strong>
            <p>Genre: {{ $book->genre->name }}</p>
            <p>Author: {{ $book->author->name }}</p>
            <p>User: <a href="{{ route('library.users.show', $book->user) }}">{{ $book->user->name }}</a></p>
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