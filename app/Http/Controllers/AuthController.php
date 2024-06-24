<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginFormRequest;
use App\Http\Requests\Auth\RegisterFormRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\CustomResponseTraits;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends Controller
{
    use CustomResponseTraits;

    public function __construct(
        protected UserService $userService
    )
    {
    }

    public function register(RegisterFormRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->safe()->only(['name', 'email', 'password']));
        return $this->successResponse(['user' => $user]);
    }

    public function login(LoginFormRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse(['message' => 'Invalid login details'], code: HttpResponse::HTTP_UNAUTHORIZED);
        }

        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return $this->successResponse(
            [
                'message' => 'Login successfully',
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
    }


    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return $this->successResponse(['message' => 'Logout Successfully'], code: HttpResponse::HTTP_NO_CONTENT);
    }


}
