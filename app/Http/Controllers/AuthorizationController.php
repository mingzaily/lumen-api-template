<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthorizationController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        // 登录
        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];
        if (!$token = Auth::attempt($credentials)) {
            $this->errorUnauthorized('username or password error');
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
