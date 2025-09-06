<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowFolderRequest;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Http\Resources\FolderResource;
use App\Traits\ApiResponse;

class FolderController extends Controller
{
    use ApiResponse;

    /**
     * List all folders of authenticated user
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = $user->role === 'admin'
            ? Folder::query()->with('user')
            : $user->folders()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->user_name}%");
            });
        }

        if ($request->filled('min_words')) {
            $query->where('words_count', '>=', (int) $request->min_words);
        }
        if ($request->filled('max_words')) {
            $query->where('words_count', '<=', (int) $request->max_words);
        }

        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $folders = $query->paginate($request->get('per_page', 20));

        return $this->successResponse(
            'Folders retrieved successfully.',
            FolderResource::collection($folders)
        );
    }



    /**
     * Create new folder
     */
    public function store(StoreFolderRequest $request): JsonResponse
    {
        if ($request->has('user_id')){
            $user = User::find($request->user_id);
            $folder = $user->folders()->create($request->validated());
        }else{
            $folder = $request->user()->folders()->create($request->validated());
        }

        return $this->successResponse('Folder created successfully.', new FolderResource($folder), 200);
    }

    /**
     * Show specific folder
     */
    public function show(Folder $folder, ShowFolderRequest $request): JsonResponse
    {
        return $this->successResponse('Folder retrieved successfully.', new FolderResource($folder));
    }

    /**
     * Update folder
     */
    public function update(UpdateFolderRequest $request, Folder $folder): JsonResponse
    {
        $folder->update($request->validated());

        return $this->successResponse('Folder updated successfully.', new FolderResource($folder));
    }

    /**
     * Delete folder
     */
    public function destroy(Folder $folder, ShowFolderRequest $request): JsonResponse
    {
        $folder->delete();
        return $this->successResponse('Folder deleted successfully.');
    }
}
