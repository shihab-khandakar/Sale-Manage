<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use \App\Http\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use App\Repositories\Common\CommonRepository;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    use ResponseTrait;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->middleware('auth:api', ['except' => ['login', "register", "logout"]]);
        $this->common = $commonRepository;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        //if ($request->phone){
        //    $roles = [
        //        'phone'           => 'required|max:11|min:11|regex:/(01)[0-9]{9}/',
        //        'password'        => 'required|min:6|string',
        //    ];
        //}
        //if ($request->email){
        //    $roles =[
        //        'email'           => 'required|max:255|email',
        //        'password'        => 'required|min:6|string',
        //    ];
        //}
        //
        //
        //$this->validate($request, $roles);

        if ($request->isMethod('post')) {
            DB::beginTransaction();

            try {
                // check email exist or not
                $credentials = request($request->phone ? ['phone', 'password'] : ['email', 'password']);

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }

            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'invalid credentials', 'status' => 401], 401);
            }
        }

        return $this->respondWithToken($token);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $rolePermission = Role::with('permissions')->where('id', auth()->user()->role_id)->first();
        if ($rolePermission){
            return response()->json(
                [
                    'user' => auth()->user(),
                    'role' => $rolePermission->name,
                    'permissions' => $rolePermission->permissions,
                ]
            );
        }else{
            return response()->json(
                [
                    'user' => auth()->user(),
                ]
            );
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 200,
            'message' => "Login Successfully",
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 360
        ]);
    }
}
