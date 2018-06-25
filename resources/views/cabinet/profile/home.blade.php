@extends('layouts.app')

@section('content')
    @include('cabinet.profile._nav')

    <a class="btn btn-success" href=" {{ route('cabinet.profile.edit') }}">Edit profile</a>
    <h1>{{ $user->name }}</h1>
    <img src="{{ asset('storage/' . $user->personal_photo) }}" alt="No photo" style="max-width: 180px; max-height: 300px;">
@endsection