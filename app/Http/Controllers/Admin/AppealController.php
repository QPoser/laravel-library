<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Library\Book\Appeal;
use App\Http\Controllers\Controller;
use App\Services\Library\AppealService;

class AppealController extends Controller
{

    /**
     * @var AppealService
     */
    private $service;

    public function __construct(AppealService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $appeals = Appeal::orderByDesc('id')->get();

        return view('admin.appeals.home', compact('appeals'));
    }

    public function accept(Appeal $appeal)
    {
        try {
            $this->service->accept($appeal);
        } catch (\DomainException $e) {
            return redirect()->route('admin.appeals.index')->with('error', $e);
        }

        return redirect()->route('admin.appeals.index')->with('success', 'Appeal ' . $appeal->id . ' has been accepted.');
    }

    public function cancel(Appeal $appeal)
    {
        try {
            $this->service->cancel($appeal);
        } catch (\DomainException $e) {
            return redirect()->route('admin.appeals.index')->with('error', $e);
        }

        return redirect()->route('admin.appeals.index')->with('success', 'Appeal ' . $appeal->id . ' has been canceled.');
    }

}
