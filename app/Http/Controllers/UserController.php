<?php
namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $service;

    /**
     * Create a new AuthController instance.
     *
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->middleware('auth:api', ['except' => ['store']]);
        $this->service = $service;
    }

    public function show()
    {
        return $this->success(Auth::user());
    }

    // 免注册
}
