<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Library\Book\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::orderByDesc('id')->get();

        return view('admin.authors.home', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $author = Author::new($request->name, true);

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

    public function update(Request $request, Author $author)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $author->update($request->only('name'));

        return redirect()->route('admin.authors.show', $author)->with('success', 'This author is successfully edited.');
    }

    public function destroy(Author $author)
    {
        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Author ' . $author->name . ' is deleted.');
    }

    public function setActive(Author $author)
    {
        try {
            $author->setActive();
        } catch (\DomainException $e) {
            return redirect()->route('admin.authors.show', $author)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.authors.show', $author)->with('success', 'Success! This author is active!');
    }

    public function setInactive(Author $author)
    {
        try {
            $author->setInactive();
        } catch (\DomainException $e) {
            return redirect()->route('admin.authors.show', $author)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.authors.show', $author)->with('success', 'Success! This author is inactive!');
    }
}
