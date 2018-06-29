@extends('layouts.app')

@section('content')
    <h1>{{ $user->name }}</h1>
    @if ($user->isWriter())
        <strong>*Writer</strong>
        <p>Subscribers: {{ $user->subscribers->count() }}</p>
        @if (!$isCurrent)
            @if ($user->hasInSubscribers($currentUser->id))
                <form action="{{ route('library.users.unsubscribe', [$user, $currentUser]) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <button class="btn btn-danger">Unsubscribe</button>
                    </div>
                </form>
            @else
                <form action="{{ route('library.users.subscribe', [$user, $currentUser]) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <button class="btn btn-primary">Subscribe</button>
                    </div>
                </form>
            @endif
        @endif
    @endif
    @if ($isCurrent)
        <p>It's you!</p>
    @endif
    <hr>
    <h2>Books:</h2>
    @if (!$user->books->isEmpty())
        @foreach($user->books as $book)
            <p><a href="{{ route('library.books.show', $book) }}">{{ $book->title }}</a></p>
        @endforeach
    @endif
    <hr>
    <h2>Bundles:</h2>
    @if (!$user->bundles->isEmpty())
        @foreach($user->bundles as $bundle)
            <p><a href="{{ route('library.bundles.show', $bundle) }}">{{ $bundle->title }}</a></p>
        @endforeach
    @endif
@endsection