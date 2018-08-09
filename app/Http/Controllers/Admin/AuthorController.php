<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Library\Book\Author;
use App\Http\Requests\Library\AuthorRequest;
use App\Services\Library\AuthorService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorController extends Controller
{

    /**
     * @var AuthorService
     */
    private $service;

    public function __construct(AuthorService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $authors = Author::orderByDesc('id')->get();

        return view('admin.authors.home', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(AuthorRequest $request)
    {
        $author = $this->service->create($request->get('name'), true);

        return redirect()->route('admin.authors.show', $author)->with('success', 'This author is successfully added.');
    }

    public function show(Author $author)
    {
        return view('admin.authors.show', compact('author'));
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(AuthorRequest $request, Author $author)
    {
        $author = $this->service->update($author, $request->only('name'));

        return redirect()->route('admin.authors.show', $author)->with('success', 'This author is successfully edited.');
    }

    public function destroy(Author $author)
    {
        $this->service->remove($author);

        return redirect()->route('admin.authors.index')->with('success', 'Author ' . $author->name . ' is deleted.');
    }

    public function setActive(Author $author)
    {
        try {
            $this->service->activate($author);
        } catch (\DomainException $e) {
            return redirect()->route('admin.authors.show', $author)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.authors.show', $author)->with('success', 'Success! This author is active!');
    }

    public function setInactive(Author $author)
    {
        try {
            $this->service->deactivate($author);
        } catch (\DomainException $e) {
            return redirect()->route('admin.authors.show', $author)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.authors.show', $author)->with('success', 'Success! This author is inactive!');
    }
}
