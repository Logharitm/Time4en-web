<?php

namespace App\Http\Controllers;

use App\Models\Folder;
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
        $query = $request->user()->folders();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        if ($request->has('created_to')) {
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
        $folder = $request->user()->folders()->create($request->validated());
        return $this->successResponse('Folder created successfully.', new FolderResource($folder), 201);
    }

    /**
     * Show specific folder
     */
    public function show(Folder $folder, Request $request): JsonResponse
    {
        if ($folder->user_id !== $request->user()->id) {
            throw new AuthorizationException('Unauthorized.');
        }

        return $this->successResponse('Folder retrieved successfully.', new FolderResource($folder));
    }

    /**
     * Update folder
     */
    public function update(UpdateFolderRequest $request, Folder $folder): JsonResponse
    {
        if ($folder->user_id !== $request->user()->id) {
            throw new AuthorizationException('Unauthorized.');
        }

        $folder->update($request->validated());

        return $this->successResponse('Folder updated successfully.', new FolderResource($folder));
    }

    /**
     * Delete folder
     */
    public function destroy(Folder $folder, Request $request): JsonResponse
    {
        if ($folder->user_id !== $request->user()->id) {
            throw new AuthorizationException('Unauthorized.');
        }

        $folder->delete();
        return $this->successResponse('Folder deleted successfully.');
    }
}
