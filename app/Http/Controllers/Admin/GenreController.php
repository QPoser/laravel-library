<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Library\Book\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenreController extends Controller
{

    public function index()
    {
        $genres = Genre::orderByDesc('id')->get();

        return view('admin.genres.home', compact('genres'));
    }

    public function create()
    {
        return view('admin.genres.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $genre = Genre::new($request->name, true);

        return redirect()->route('admin.genres.show', $genre)->with('success', 'This genre is successfully added.');
    }

    public function show(Genre $genre)
    {
        return view('admin.genres.show', compact('genre'));
    }

    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    public function update(Request $request, Genre $genre)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $genre->update($request->only('name'));

        return redirect()->route('admin.genres.show', $genre)->with('success', 'This genre is successfully edited.');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Genre ' . $genre->name . ' is deleted.');
    }

    public function setActive(Genre $genre)
    {
        try {
            $genre->setActive();
        } catch (\DomainException $e) {
            return redirect()->route('admin.genres.show', $genre)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.genres.show', $genre)->with('success', 'Success! This genre is active!');
    }

    public function setInactive(Genre $genre)
    {
        try {
            $genre->setInactive();
        } catch (\DomainException $e) {
            return redirect()->route('admin.genres.show', $genre)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.genres.show', $genre)->with('success', 'Success! This genre is inactive!');
    }
}
