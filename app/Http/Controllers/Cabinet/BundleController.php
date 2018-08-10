<?php

namespace App\Http\Controllers\Cabinet;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Bundle;
use App\Events\Library\BundleCreated;
use App\Http\Requests\Library\BundleRequest;
use Auth;
use Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BundleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('cabinet.bundles.home', compact('user'));
    }

    public function create()
    {
        return view('cabinet.bundles.create');
    }

    public function store(BundleRequest $request)
    {
        $bundle = Bundle::new($request->title, $request->description, Auth::user());

        event(new BundleCreated($bundle));

        return redirect()->route('cabinet.bundles.show', compact('bundle'));
    }

    public function show(Bundle $bundle)
    {
        $user = Auth::user();

        return view('cabinet.bundles.show', compact('bundle', 'user'));
    }

    public function addToBundle(Bundle $bundle, Book $book)
    {
        if (!Gate::allows('manage-own-book', $book)) {
            abort(403);
        }

        try {
            $bundle->addToBundle($book);
        } catch (\DomainException $e) {
            return redirect()->route('cabinet.bundles.show', $bundle)->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.bundles.show', $bundle)->with('success', 'Book ' . $book->title . ' successfully added.');
    }

    public function removeFromBundle(Bundle $bundle, Book $book)
    {
        try {
            $bundle->removeFromBundle($book);
        } catch (\DomainException $e) {
            return redirect()->route('cabinet.bundles.show', $bundle)->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.bundles.show', $bundle)->with('success', 'Book ' . $book->title . ' successfully removed.');
    }

    public function destroy(Bundle $bundle)
    {
        $bundle->delete();

        return redirect()->route('cabinet.bundles.home');
    }
}
