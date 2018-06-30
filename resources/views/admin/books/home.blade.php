@extends('layouts.app')

@section('content')
    @include('admin.books._nav')

    <h1>Books:</h1>
    <a class="btn btn-success" href=" {{ route('admin.books.create') }}">Create book</a>
    <hr>
    <div class="card mb-3">
        <div class="card-header">Filter</div>
        <div class="card-body">
            <form action="?" method="GET">
                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label for="id" class="col-form-label">ID</label>
                            <input id="id" class="form-control" name="id" value="{{ request('id') }}">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="title" class="col-form-label">Title</label>
                            <input id="title" class="form-control" name="title" value="{{ request('title') }}">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="author" class="col-form-label">Author</label>
                            <select id="author" class="form-control" name="author_id">
                                <option value=""></option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}" {{ request('author_id') == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="genre" class="col-form-label">Genre</label>
                            <select id="genre" class="form-control" name="genre_id">
                                <option value=""></option>
                                @foreach ($genres as $genre)
                                    <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="status" class="col-form-label">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option value=""></option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="col-form-label">&nbsp;</label><br />
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thread>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>User</th>
                <th>Status</th>
                <th>File</th>
                <th>Actions</th>
            </tr>
        </thread>

        @foreach($books as $book)
            <tr>
                <td>{{ $book->id }}</td>
                <td><a href=" {{ route('admin.books.show', $book) }} ">{{ $book->title }}</a></td>
                <td><a href=" {{ route('admin.authors.show', $book->author) }} ">{{ $book->author->name }}</a></td>
                <td><a href=" {{ route('admin.genres.show', $book->genre) }} ">{{ $book->genre->name }}</a></td>
                <td><a href=" {{ route('admin.users.show', $book->user) }} ">{{ $book->user->name }}</a></td>
                @if ($book->isActive())
                    <td><span class="badge badge-primary">{{ $book->status }}</span></td>
                @else
                    <td><span class="badge badge-warning">{{ $book->status }}</span></td>
                @endif
                <td><a href=" {{ asset('storage/' . $book->file_path) }} " class="btn btn-success" download>Download</a></td>
                <td>
                    <div class="row">
                        @if (!$book->isActive())
                            <form action="{{ route('admin.books.set-active', $book) }}" method="POST">
                                @csrf

                                <button class="btn btn-success" type="submit">Set active</button>
                            </form>
                        @else
                            <form action="{{ route('admin.books.set-inactive', $book) }}" method="POST">
                                @csrf

                                <button class="btn btn-warning" type="submit">Set inactive</button>
                            </form>
                        @endif
                        <span>&nbsp&nbsp</span>
                        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-primary">Edit</a>
                        <span>&nbsp&nbsp</span>
                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST">
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