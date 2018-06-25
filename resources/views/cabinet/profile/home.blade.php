@extends('layouts.app')

@section('content')
    @include('cabinet.profile._nav')

    <a class="btn btn-success" href=" {{ route('cabinet.profile.edit') }}">Edit profile</a>
    <h1>{{ $user->name }}</h1>
    <img src="{{ $user->profile_photo }}" alt="No photo">
@endsection