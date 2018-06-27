@extends('layouts.app')

@section('content')
    @include('cabinet.bundles._nav')

    <h1>{{ $bundle->title }}</h1>
    <p>{{ $bundle->description }}</p>
    <ul>
        @foreach($user->books as $book)
            <li>{{ $book->title }}
                @if(!$bundle->hasInBundle($book->id))
                    <form action="{{ route('cabinet.bundles.add-book', [$bundle, $book]) }}" method="POST">
                        @csrf
                        <button class="btn btn-success" type="submit">Add</button>
                    </form>
                @else
                    <form action="{{ route('cabinet.bundles.remove-book', [$bundle, $book]) }}" method="POST">
                        @csrf
                        <button class="btn btn-warning" type="submit">Remove</button>
                    </form>
                @endif
            </li>
        @endforeach
    </ul>
    <form action="{{ route('cabinet.bundles.remove', $bundle) }}" method="POST">
        @csrf
        <button class="btn btn-danger" type="submit">Delete</button>
    </form>
@endsection