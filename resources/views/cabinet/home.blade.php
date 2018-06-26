@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a href="{{ route('cabinet.home') }}" class="nav-link active">Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('cabinet.profile.home') }}" class="nav-link">Profile</a></li>
        <li class="nav-item"><a href="{{ route('cabinet.books.home') }}" class="nav-link">Books</a></li>
    </ul>
@endsection