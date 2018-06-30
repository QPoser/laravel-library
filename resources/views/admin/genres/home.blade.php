@extends('layouts.app')

@section('content')
    @include('admin.genres._nav')

    <h1>Genres:</h1>
    <a class="btn btn-success" href=" {{ route('admin.genres.create') }}">Create genre</a>
    <hr>
    <table class="table table-bordered table-striped">
        <thread>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thread>

        @foreach($genres as $genre)
            <tr>
                <td>{{ $genre->id }}</td>
                <td><a href=" {{ route('admin.genres.show', $genre) }} "><b>{{ $genre->name }}</b></a></td>
                @if ($genre->isActive())
                    <td><span class="badge badge-success">{{ $genre->status }}</span></td>
                @else
                    <td><span class="badge badge-warning">{{ $genre->status }}</span></td>
                @endif
                <td>
                    <div class="row">
                    @if ($genre->isActive())
                        <form action="{{ route('admin.genres.set-inactive', $genre) }}" method="POST">
                            @csrf
                            <button class="btn btn-warning" type="submit">Set inactive</button>
                        </form>
                    @else
                        <form action="{{ route('admin.genres.set-active', $genre) }}" method="POST">
                            @csrf
                            <button class="btn btn-success" type="submit">Set active</button>
                        </form>
                    @endif
                        <span>&nbsp&nbsp</span>
                        <a href="{{ route('admin.genres.edit', $genre) }}" class="btn btn-primary">Edit</a>
                        <span>&nbsp&nbsp</span>
                        <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
@endsection