@extends('layouts.app')

@section('content')
    <h1>Bundles</h1>
    <hr>
    @foreach($bundles as $bundle)
        <h4><a href=" {{ route('library.bundles.show', $bundle) }}">{{ $bundle->title }}</a></h4>
    @endforeach
@endsection