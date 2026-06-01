<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Requests\LoginRequest;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function register()
    {
        //
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
            return $this->successResponse(null, 'SuccessFully Logged out', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function me()
    {
        try {
            return $this->successResponse(auth()->user(), 'SuccessFully Logged out', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
