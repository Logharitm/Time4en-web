<?php

namespace App\Http\Controllers;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
        dd(1);
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

    /**
     * Update authenticated user profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->validated());

        return $this->successResponse('Profile updated successfully.', [
            'user' => new UserResource($user)
        ],200);
    }

    /**
     * Change authenticated user's password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        if (!\Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password does not match.'],
            ]);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return $this->successResponse('Password updated successfully.');
    }

    /**
     * Send password reset link
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => ['Unable to send password reset link.'],
            ]);
        }

        return $this->successResponse('Password reset link sent to your email.');
    }

    /**
     * Reset password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->password = bcrypt($request->password);
                $user->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->successResponse('Password has been reset successfully.');
        }

        // استخدام ValidationException لربط الخطأ بحقل email
        throw ValidationException::withMessages([
            'email' => ['Invalid token or email.'],
        ]);
    }


}
