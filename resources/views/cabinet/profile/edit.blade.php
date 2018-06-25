@extends('layouts.app')

@section('content')
    @include('cabinet.profile._nav')

    <form action="{{ route('cabinet.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="col-form-label">Name</label>
            <input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $user->name) }}" required>
            @if ($errors->has('name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="personal_photo" class="col-form-label"><img src="{{ $user->personal_photo ? asset('storage/' . $user->personal_photo) : 'Photo' }}" alt="No photo" style="max-width: 120px; max-height: 200px;"></label>
            <input id="personal_photo" class="form-control{{ $errors->has('personal_photo') ? ' is-invalid' : '' }}" name="personal_photo" type="file">
            @if ($errors->has('personal_photo'))
                <span class="invalid-feedback"><strong>{{ $errors->first('personal_photo') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
@endsection