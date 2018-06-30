@extends('layouts.app')

@section('content')
    @include('admin.authors._nav')

    <h1>Authors:</h1>
    <a class="btn btn-success" href=" {{ route('admin.authors.create') }}">Create author</a>
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

        @foreach($authors as $author)
            <tr>
                <td>{{ $author->id }}</td>
                <td><a href=" {{ route('admin.authors.show', $author) }} "><b>{{ $author->name }}</b></a></td>
                @if ($author->isActive())
                    <td><span class="badge badge-success">{{ $author->status }}</span></td>
                @else
                    <td><span class="badge badge-warning">{{ $author->status }}</span></td>
                @endif
                <td>
                    <div class="row">
                    @if ($author->isActive())
                        <form action="{{ route('admin.authors.set-inactive', $author) }}" method="POST">
                            @csrf
                            <button class="btn btn-warning" type="submit">Set inactive</button>
                        </form>
                    @else
                        <form action="{{ route('admin.authors.set-active', $author) }}" method="POST">
                            @csrf
                            <button class="btn btn-success" type="submit">Set active</button>
                        </form>
                    @endif
                        <span>&nbsp&nbsp</span>
                        <a href="{{ route('admin.authors.edit', $author) }}" class="btn btn-primary">Edit</a>
                        <span>&nbsp&nbsp</span>
                        <form action="{{ route('admin.authors.destroy', $author) }}" method="POST">
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