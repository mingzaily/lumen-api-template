<?php
namespace App\Http\Controllers;

use App\Constants\ErrCode;
use App\Exceptions\RenderException;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthorizationController extends Controller
{
    protected $userService;

    /**
     * Create a new AuthController instance.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
            ['username' => 'required', 'password' => 'required']
        );

        throw new RenderException(ErrCode::OwnServer, 'abcdefg');
        // 登录
        $credentials = $request->only(['username', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            $this->errorUnauthorized();
        }
        return $this->respondWithToken($token);
    }

    /**
     * @return JsonResponse
     */
    public function destroy()
    {
        auth()->logout();
        return $this->noContent('Successfully logged out');
    }

    /**
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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
            'expires_time' => Carbon::now()->addMinutes(auth()->factory()->getTTL())->toDateTimeString()
        ]);
    }
}
