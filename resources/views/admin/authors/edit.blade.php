@extends('layouts.app')

@section('content')
    @include('admin.authors._nav')
    <h2>Edit author</h2>
    <form action="{{ route('admin.authors.update', $author) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="col-form-label">Name</label>
            <input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $author->name) }}" required>
            @if ($errors->has('name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
@endsection