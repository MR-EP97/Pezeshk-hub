<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginFormRequest;
use App\Http\Requests\Auth\RegisterFormRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\CustomResponseTraits;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends Controller
{
    use CustomResponseTraits;

    public function __construct(
        protected UserService $userService
    )
    {
    }


    /**
     *
     * @OA\Post(
     *      path="/api/register",
     *      tags={"Auth"},
     *      summary="Register a new user",
     *      description="Register a new user with name, email, and password",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="password")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User registered successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *              @OA\Property(property="token", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request"
     *      )
     *  )
     *
     *
     * @param RegisterFormRequest $request
     * @return JsonResponse
     */
    public function register(RegisterFormRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->safe()->only(['name', 'email', 'password']));
        return $this->successResponse('User Registered successfully', ['user' => UserResource::make($user)], HttpResponse::HTTP_CREATED);
    }


    /**
     *
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Auth"},
     *      summary="Log in a user",
     *      description="Log in a user with email and password",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User logged in successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *              @OA\Property(property="token", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     *  )
     *
     * @param LoginFormRequest $request
     * @return JsonResponse
     */
    public function login(LoginFormRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('Invalid login details', code: HttpResponse::HTTP_UNAUTHORIZED);
        }

        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return $this->successResponse(
            'Login successfully',
            [
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
    }

    /**
     * @OA\Post(
     *       path="/api/logout",
     *       tags={"Auth"},
     *       summary="Logout",
     *       description="Logout user",
     *       security={{"bearerAuth":{}}},
     *            @OA\SecurityScheme(
     *            securityScheme="bearerAuth",
     *            type="http",
     *            scheme="bearer",
     *            bearerFormat="token"
     *        ),
     *       @OA\Response(
     *           response=200,
     *           description="User logged out successfully",
     *       ),
     *       @OA\Response(
     *           response=401,
     *           description="Unauthorized"
     *       )
     *   )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return $this->successResponse('Logout Successfully', code: HttpResponse::HTTP_NO_CONTENT);
    }


}
