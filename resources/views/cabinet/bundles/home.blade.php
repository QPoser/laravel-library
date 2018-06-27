@extends('layouts.app')

@section('content')
    @include('cabinet.bundles._nav')

    <h1>{{ ucfirst($user->name) }} bundles:</h1>
    <a class="btn btn-success" href=" {{ route('cabinet.bundles.create') }}">Create bundle</a>
    <hr>
    @if($user->bundles->isEmpty())
        <p>You have not bundles.</p>
    @else
        @foreach($user->bundles as $bundle)
            <p><a href=" {{ route('cabinet.bundles.show', $bundle) }} "><b>{{ $bundle->title }}</b></a></p>
            <ul>
                @if($bundle->books->isEmpty())
                    <li>This bundle has not books</li>
                @else
                    @foreach($bundle->books as $book)
                        <li>{{ $book->title }}</li>
                    @endforeach
                @endif
            </ul>
        @endforeach
    @endif

@endsection