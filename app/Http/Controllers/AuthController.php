<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if (!$user) {
                throw new Exception('failed to create user', 422);
            }

            return $this->successResponse(UserResource::make($user), 'successfully created user', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            $token = auth()->attempt($credentials); 

            if(!$token) {
                throw new Exception('Unauthorized', 401);
            }

            return $this->successResponse(null, 'successfully logged in', 200)->cookie(
                'jwt_token',        // name cookie
                $token,             // value token
                60,                 // expires in minutes
                '/',                // path
                null,               // domain
                true,               // secure (HTTPS only — set false in local)
                true,               // httpOnly
                false,              // raw
                'strict'            // sameSite
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function refresh()
    {
        return $this->successResponse(auth()->refresh(), 'Token refreshed', 200);    
    }

    public function logout()
    {
        try {
            auth()->logout();
            return $this->successResponse(null, 'successfully logged out', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function me()
    {
        try {
            $auth = auth()->user();

            if (!$auth) {
                throw new Exception('Unauthorized', 401);
            }
            return $this->successResponse($auth, 'SuccessFully get user', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
