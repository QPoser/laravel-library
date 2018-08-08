@extends('layouts.app')

@section('content')
    <h1>{{ $book->title }}</h1>
    @if ($bookStars > 0)
        <p>Stars: {{ $bookStars }}</p>
    @endif
    <p>Genre: {{ $book->genre->name }}</p>
    <p>Author: {{ $book->author->name }}</p>
    <p>User: <a href="{{ route('library.users.show', $book->user) }}">{{ $book->user->name }}</a></p>
    <p>{{ $book->description }}</p>
    <a href="{{ asset('storage/' . $book->file_path) }}" class="btn btn-success" download>Download</a>
    @auth
        <a href="{{ route('library.books.appeal.add', $book) }}" class="btn btn-danger">Add appeal</a>
    @endauth
    <hr>
    @if(!$book->reviews->isEmpty())
        <h2>Reviews</h2>
        <hr>
        @foreach($book->reviews as $review)
            <p><strong>Author: {{ $review->user->name }}</strong></p>
            <b>Stars: {{ $review->stars }}</b>
            <p>Review: {{ $review->review }}</p>
        @endforeach
    @endif

    @auth
        <hr>
        <form action="{{ route('library.books.review.add', $book) }}" method="post">
            @csrf

            <div class="form-group">
                <label for="stars" class="col-form-label">Stars</label>
                <select name="stars" id="stars">
                    @foreach($stars as $star)
                        <option value="{{ $star }}">{{ $star }}</option>
                    @endforeach
                </select>
                @if ($errors->has('stars'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('stars') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label for="review" class="col-form-label">Review</label>
                <textarea id="review" class="form-control{{ $errors->has('review') ? ' is-invalid' : '' }}" name="review" required>{{ old('review') }}</textarea>
                @if ($errors->has('review'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('review') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <button class="btn btn-primary" type="submit">Add review</button>
            </div>
        </form>
    @endauth
@endsection