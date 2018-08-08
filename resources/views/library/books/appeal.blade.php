@extends('layouts.app')

@section('content')

    <h1>Add appeal for {{ $book->title }}</h1>

    <form action="{{ route('library.books.appeal.store', $book) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="reason" class="col-form-label">Reason</label>
            <input id="reason" class="form-control{{ $errors->has('reason') ? ' is-invalid' : '' }}" name="reason" value="{{ old('reason') }}" required>
            @if ($errors->has('reason'))
                <span class="invalid-feedback"><strong>{{ $errors->first('reason') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Add</button>
        </div>
    </form>

@endsection