@extends('layouts.app')

@section('content')
    @include('cabinet.books._nav')

    <form action="{{ route('cabinet.books.update', $book) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title" class="col-form-label">Name</label>
            <input id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title', $book->title) }}" required>
            @if ($errors->has('title'))
                <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="description" class="col-form-label">Description</label>
            <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" required>{{ old('description', $book->description) }}</textarea>
            @if ($errors->has('description'))
                <span class="invalid-feedback"><strong>{{ $errors->first('description') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="genre" class="col-form-label">Genre</label>
            <select name="genre" id="genre">
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" {{ $genre->id == $book->genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('genre'))
                <span class="invalid-feedback"><strong>{{ $errors->first('genre') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="author" class="col-form-label">Author</label>
            <select name="author" id="author">
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}" {{ $author->id == $book->author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('author'))
                <span class="invalid-feedback"><strong>{{ $errors->first('author') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="file" class="col-form-label">File</label>
            <p><a href="{{ asset('storage/' . $book->file_path) }}" download>Download</a></p>
            <input id="file" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" name="file" type="file">
            @if ($errors->has('file'))
                <span class="invalid-feedback"><strong>{{ $errors->first('file') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
@endsection