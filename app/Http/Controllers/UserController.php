<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = User::query()->with(['subscription.plan']);

        if ($request->has('role') && in_array($request->role, ['admin', 'user'])) {
            $query->where('role', $request->role);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('plaapp/Http/Controllers/UserController.phpn_id')) {
            $query->whereHas('subscription.plan', function ($q) use ($request) {
                $q->where('id', $request->plan_id);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereHas('subscription', function ($q) use ($request) {
                $q->whereDate('start_date', '>=', $request->start_date);
            });
        }

        if ($request->filled('end_date')) {
            $query->whereHas('subscription', function ($q) use ($request) {
                $q->whereDate('end_date', '<=', $request->end_date);
            });
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate($request->get('per_page', 20));

        return $this->successResponse(
            'Users retrieved successfully.',
            UserResource::collection($users)
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        if ($request->hasFile('avatar') && $request->file('avatar') != null) {
            $avatar = $request->file('avatar')->store('avatar', 'public');
            $user->avatar = $avatar;
            $user->update();
        }


        return $this->successResponse('User created successfully.', new UserResource($user), 200);
    }

    public function show(User $user): JsonResponse
    {
        return $this->successResponse('User retrieved successfully.', new UserResource($user));
    }

    public function update(UpdateUserRequest $request, $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        $data = $request->validated();
        if (isset($data['password']) && $data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('avatar') && $request->file('avatar') != null) {
            if ($user->avatar) {
                $oldPath = str_replace(asset('storage/'), '', $user->avatar);
                Storage::disk('public')->delete($oldPath);
            }

            $avatar = $request->file('avatar')->store('avatar', 'public');

            $user->avatar = $avatar;
        }

        $user->update($data);

        return $this->successResponse('User updated successfully.', new UserResource($user));
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return $this->successResponse('User deleted successfully.');
    }
}
