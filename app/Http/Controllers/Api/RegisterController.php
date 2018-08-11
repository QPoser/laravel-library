<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\RegisterService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    /**
     * @var RegisterService
     */
    private $service;

    public function __construct(RegisterService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        $this->service->register($request);

        return response()->json([
                'success' => 'Check your email and click on the link to verify.',
        ], Response::HTTP_CREATED);
    }
}
