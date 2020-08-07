<?php
namespace App\Http\Controllers;

use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthorizationController extends Controller
{
    protected $service;

    /**
     * Create a new AuthController instance.
     *
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        // 数据验证
        $this->validate($request,
            ['code' => 'required'],
            ['code.required' => '认证码不能为空']
        );

        // 登录
        $user_id = $this->service->check($request->all());
        if (!$token = Auth::loginUsingId($user_id)) {
            $this->errorUnauthorized();
        }
        return $this->respondWithToken($token);
    }

    /**
     * @return JsonResponse
     */
    public function destroy()
    {
        Auth::logout();
        return $this->noContent('Successfully logged out');
    }

    /**
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::getFacadeRoot()->refresh());
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_time' => Carbon::now()->addMinutes(Auth::getFacadeRoot()->factory()->getTTL())->toDateTimeString()
        ]);
    }
}
