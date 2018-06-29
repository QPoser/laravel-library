@extends('layouts.app')

@section('content')
    @include('cabinet.profile._nav')

    <a class="btn btn-success" href=" {{ route('cabinet.profile.edit') }}">Edit profile</a>
    <h1>{{ $user->name }}</h1>
    <img src="{{ asset('storage/' . $user->personal_photo) }}" alt="No photo" style="max-width: 180px; max-height: 300px;">
    <br>
    <br>
    @if ($user->isWriter())
        <form action="{{ route('cabinet.profile.become_not_writer') }}" method="POST">
            @csrf

            <div class="form-group">
                <button class="btn btn-warning" type="submit">
                    I'm not a writer!
                </button>
            </div>
        </form>
    @else
        <form action="{{ route('cabinet.profile.become_writer') }}" method="POST">
            @csrf

            <div class="form-group">
                <button class="btn btn-primary" type="submit">
                    I'm a writer!
                </button>
            </div>
        </form>
    @endif
@endsection