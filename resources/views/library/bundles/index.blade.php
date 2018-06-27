@extends('layouts.app')

@section('content')
    <h1>Bundles</h1>

    @foreach($bundles as $bundle)
        <h2><a href=" {{ route('library.bundles.show', $bundle) }}">{{ $bundle->title }}</a></h2>
    @endforeach
@endsection