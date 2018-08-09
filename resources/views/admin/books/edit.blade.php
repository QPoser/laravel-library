@extends('layouts.app')

@section('content')
    @include('admin.books._nav')
    <h2>Edit book</h2>
    <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title" class="col-form-label">Title</label>
            <input id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title', $book->title) }}" required>
            @if ($errors->has('title'))
                <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="description" class="col-form-label">Description</label>
            <textarea name="description" id="description" cols="30" rows="10" class="form-control form-text{{ $errors->has('email') ? ' is-invalid' : '' }}" required>{{ old('description', $book->description) }}</textarea>
            @if ($errors->has('description'))
                <span class="invalid-feedback"><strong>{{ $errors->first('description') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="author" class="col-form-label">Author</label>
            <select name="genre" id="author" required>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}" {{ old('author', $book->author->id) == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('author'))
                <span class="invalid-feedback"><strong>{{ $errors->first('author') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="genre" class="col-form-label">Genre</label>
            <select name="genre" id="genre" required>
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" {{ old('genre', $book->genre->id) == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('genre'))
                <span class="invalid-feedback"><strong>{{ $errors->first('genre') }}</strong></span>
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