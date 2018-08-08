<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Library\Book\Appeal;
use App\Http\Controllers\Controller;

class AppealController extends Controller
{

    public function index()
    {
        $appeals = Appeal::orderByDesc('id')->get();

        return view('admin.appeals.home', compact('appeals'));
    }

    public function accept(Appeal $appeal)
    {
        $appeal->accept();

        return redirect()->route('admin.appeals.index')->with('success', 'Appeal ' . $appeal->id . ' has been accepted.');
    }

    public function cancel(Appeal $appeal)
    {
        $appeal->cancel();

        return redirect()->route('admin.appeals.index')->with('success', 'Appeal ' . $appeal->id . ' has been canceled.');
    }

}
