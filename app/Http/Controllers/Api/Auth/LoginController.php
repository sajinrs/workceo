<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-11-2020
 * Time: PM 04:04
 */

namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Front\FrontBaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoginController extends FrontBaseController
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {

        $credentials = request(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if($this->getUserRole() != 'super-admin'){
            $refresh_token = auth('api')->setTTL(env('JWT_REFRESH_TTL'))->attempt($credentials);
            return $this->respondWithToken($token, $refresh_token);
        }else{
            auth('api')->logout();
            return response()->json(['error' => 'Unauthorized'], 403);
        }

    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if(auth('api')->user()){
            auth('api')->logout();
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        try{
            return $this->respondWithRefreshToken(auth('api')->refresh());
        }catch (Exception $exception){
            return response()->json(['error' => $exception->getMessage()],400);
        }

    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $refresh_token)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refresh_token,
            'token_type' => 'bearer',
            'expires_in' => $this->expireInSeconds(),
            'user_role' => $this->getUserRole(),
            'current_time' => time()
        ]);

        /*return response()->json([
            'token' => $token,
             'expires' => auth('api')->factory()->getTTL() * 60,
            ]);*/

    }

    protected function respondWithRefreshToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->expireInSeconds()
            ]);

    }

    protected function getUserRole()
    {
        $user = auth('api')->user();
        $role = '';

        if ($user->super_admin == '1') {
            $role = 'super-admin';
        } elseif ($user->hasRole('admin')) {
            $user->company()->update([
                'last_login' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            $role = 'admin';
        }elseif ($user->hasRole('employee')) {
            $role = 'member';
        }elseif ($user->hasRole('client')) {
            $role = 'client';
        }

        return $role;
    }

    protected function expireInSeconds(){
        return ((auth('api')->factory()->getTTL()-60) * 60);
    }
}
