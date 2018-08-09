@extends('layouts.app')

@section('content')
    @include('admin.users._nav')
    <h2>Edit user</h2>
    @if ($errors->has('is_writer'))
        <span class="invalid-feedback"><strong>{{ $errors->first('is_writer') }}</strong></span>
    @endif
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
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
            <label for="email" class="col-form-label">Email</label>
            <input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" type="email" value="{{ old('email', $user->email) }}" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback"><strong>{{ $errors->first('email') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="role" class="col-form-label">Role</label>
            <select name="role" id="role" required>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" {{ $role == $user->role ? 'selected' : '' }}>{{ $role }}</option>
                @endforeach
            </select>
            @if ($errors->has('role'))
                <span class="invalid-feedback"><strong>{{ $errors->first('role') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <input type="checkbox" id="is_writer" class="form-check-input {{ $errors->has('is_writer') ? ' is-invalid' : '' }}" name="is_writer" {{ old('is_writer', $user->is_writer) ? 'checked' : '' }}>
            <label for="is_writer" class="col-form-label">Is writer</label>
            @if ($errors->has('is_writer'))
                <span class="invalid-feedback"><strong>{{ $errors->first('is_writer') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
@endsection