@extends('layouts.app')

@section('content')
    @include('cabinet.books._nav')

    <form action="{{ route('cabinet.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title" class="col-form-label">Name</label>
            <input id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title') }}" required>
            @if ($errors->has('title'))
                <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="description" class="col-form-label">Description</label>
            <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" required>{{ old('description') }}</textarea>
            @if ($errors->has('description'))
                <span class="invalid-feedback"><strong>{{ $errors->first('description') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="genre" class="col-form-label">Genre</label>
            @if (!$genres->isEmpty())
                <select name="genre" id="genre">
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @endforeach
                </select>
            @else
                <input id="genre" class="form-control{{ $errors->has('genre') ? ' is-invalid' : '' }}" name="genre" value="{{ old('genre') }}" required>
            @endif
            @if ($errors->has('genre'))
                <span class="invalid-feedback"><strong>{{ $errors->first('genre') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="author" class="col-form-label">Author</label>
            @if (!$authors->isEmpty())
                <select name="author" id="author">
                    @foreach ($authors as $author)
                        <option value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>
            @else
                <input id="author" class="form-control{{ $errors->has('author') ? ' is-invalid' : '' }}" name="author" value="{{ old('author') }}" required>
            @endif
            @if ($errors->has('author'))
                <span class="invalid-feedback"><strong>{{ $errors->first('author') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="file" class="col-form-label">File</label>
            <input id="file" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" name="file" type="file" required>
            @if ($errors->has('file'))
                <span class="invalid-feedback"><strong>{{ $errors->first('file') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
@endsection