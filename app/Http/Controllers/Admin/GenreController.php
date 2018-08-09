<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Library\Book\Genre;
use App\Http\Requests\Library\GenreRequest;
use App\Services\Library\GenreService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenreController extends Controller
{

    /**
     * @var GenreService
     */
    private $service;

    public function __construct(GenreService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $genres = Genre::orderByDesc('id')->get();

        return view('admin.genres.home', compact('genres'));
    }

    public function create()
    {
        return view('admin.genres.create');
    }

    public function store(GenreRequest $request)
    {
        $genre = $this->service->create($request->name, true);

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

    public function update(GenreRequest $request, Genre $genre)
    {
        $this->service->update($genre, $request->only('name'));

        return redirect()->route('admin.genres.show', $genre)->with('success', 'This genre is successfully edited.');
    }

    public function destroy(Genre $genre)
    {
        $this->service->remove($genre);

        return redirect()->route('admin.genres.index')->with('success', 'Genre ' . $genre->name . ' is deleted.');
    }

    public function setActive(Genre $genre)
    {
        try {
            $this->service->activate($genre);
        } catch (\DomainException $e) {
            return redirect()->route('admin.genres.show', $genre)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.genres.show', $genre)->with('success', 'Success! This genre is active!');
    }

    public function setInactive(Genre $genre)
    {
        try {
            $this->service->deactivate($genre);
        } catch (\DomainException $e) {
            return redirect()->route('admin.genres.show', $genre)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.genres.show', $genre)->with('success', 'Success! This genre is inactive!');
    }
}
