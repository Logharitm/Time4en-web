<?php

namespace App\Http\Controllers;
use App\Events\UserRegistered;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserNotificationResource;
use App\Http\Resources\UserResource;
use App\Mail\ResetPasswordMailAr;
use App\Mail\ResetPasswordMailEn;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Storage;
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

        // Create token
        $tokenResult = $user->createToken('Personal Access Token');
        $plan = Plan::find(1);

        $payment = new Payment();
        $payment->amount = 0;
        $payment->payment_method = 'trial';
        $payment->status = 'completed';

        event(new Registered($user));
        event(new UserRegistered($user,$plan,$payment));

        // Return success response with formatted user resource
        return $this->successResponse('User successfully registered.', [
            'accessToken' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 200);

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
        $user->device_token = $request->device_token;
        $user->save();

        if (is_null($user->email_verified_at)) {
            return response()->json([
                'message' => 'من فضلك فعّل بريدك الإلكتروني أولاً'
            ], 403);
        }

        $tokenResult = $user->createToken('Personal Access Token');



        return $this->successResponse('User successfully login.', [
            'accessToken' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer',
            'name' => $user->name,
            'role' => $user->role,
            'avatar' => $user->avatar,
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

        if ($request->hasFile('avatar') && $request->file('avatar') != null) {
            if ($user->avatar) {
                $oldPath = str_replace(asset('storage/'), '', $user->avatar);
                Storage::disk('public')->delete($oldPath);
            }

            $avatar = $request->file('avatar')->store('avatar', 'public');

            $user->avatar = $avatar;
        }


        $user->update();

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

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['هذا البريد غير مسجل لدينا.'],
            ]);
        }

        $status = Password::createToken($user);

        if (!$status) {
            throw ValidationException::withMessages([
                'email' => ['تعذر إرسال رابط إعادة تعيين كلمة المرور.'],
            ]);
        }

        // اختيار اللغة
        if ($user->language === 'ar') {
            Mail::to($user->email)->send(new ResetPasswordMailAr($user, $status));
        } else {
            Mail::to($user->email)->send(new ResetPasswordMailEn($user, $status));
        }

        return response()->json([
            'message' => 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.'
        ]);
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

        throw ValidationException::withMessages([
            'email' => ['Invalid token or email.'],
        ]);
    }

    public function updateDeviceToken(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->device_token = $request->device_token;
        $user->update();
        return $this->successResponse('User updated successfully.', new UserResource($user));
    }

    public function subscribe(Request $request): JsonResponse
    {
        $user = $request->user();
        $plan = Plan::findOrFail($request->plan_id);
        $payment = new Payment();
        $payment->amount = $request->amount;
        $payment->payment_method = $request->payment_method;
        $payment->status = $request->status;

        event(new UserRegistered($user,$plan,$payment));

        // Return success response with formatted user resource
        return $this->successResponse('User successfully subscriped.', [
            'user' => new UserResource($user),
        ], 200);

    }

    public function userNotification(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Notification::where('user_id',$user->id)
            ->orderBy('created_at', 'desc');

        $query->when($request->has('is_read'), function ($q) use ($request) {
            $isRead = $request->boolean('is_read');
            $q->where('is_read', $isRead);
        });

        $perPage = $request->input('per_page', 20);


        $notifications = $query->paginate($perPage);

        return $this->successResponse(
            'Notifications retrieved successfully.',
            UserNotificationResource::collection($notifications)
        );
    }

}
