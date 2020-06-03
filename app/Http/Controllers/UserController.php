<?php
namespace App\Http\Controllers;

use App\Constants\StatusConstant;
use App\Exceptions\RenderException;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // 数据验证
        $this->validate($request,
            ['username' => 'required', 'password' => 'required'],
            ['username.required' => '用户名不能为空', 'password.required' => '密码不能为空']
        );

        // 服务
        $this->service->register($request->all());
        return $this->created();
    }
}
