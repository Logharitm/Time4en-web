<?php

namespace App\Http\Controllers;
use App\Exceptions\CustomAuthenticationException;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $validated = $request->validated();


        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Create token
        $tokenResult = $user->createToken('Personal Access Token');

        // Return success response with formatted user resource
        return $this->successResponse('User successfully registered.', [
            'accessToken' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 201);

    }


    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     */

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();

        if(!Auth::attempt($credentials))
        {
            throw new AuthenticationException('Invalid credentials provided.');
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        return $this->successResponse('User successfully login.', [
            'accessToken' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer',
        ], 200);

    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return $this->successResponse('User successfully returned data.', [
            'user' => new UserResource($request->user()),
        ], 200);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);

    }
}
