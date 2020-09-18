<?php
namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * Create a new UserBaseController instance.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    /**
     * @return JsonResponse
     */
    public function show()
    {
        return $this->success(auth()->user());
    }
}
