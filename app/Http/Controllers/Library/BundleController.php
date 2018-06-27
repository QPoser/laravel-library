<?php

namespace App\Http\Controllers\Library;

use App\Entities\Library\Book\Bundle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BundleController extends Controller
{
    public function show(Bundle $bundle)
    {
        return view('library.bundles.show', compact('bundle'));
    }

    public function index()
    {
        $bundles = Bundle::all();

        return view('library.bundles.index', compact('bundles'));
    }
}
