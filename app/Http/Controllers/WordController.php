<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowWordRequest;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWordRequest;
use App\Http\Requests\UpdateWordRequest;
use App\Http\Resources\WordResource;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;

class WordController extends Controller
{
    use ApiResponse;

    /**
     * List all words in a folder with search and filter
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $query = Word::query();
        } else {
            $query = Word::whereHas('folder', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($request->has('folder_id') && !empty($request->folder_id)) {
            $folderId = $request->folder_id;
            if ($user->role === 'admin') {
                $query->where('folder_id', $folderId);
            } else {
                $query->where('folder_id', $folderId)
                    ->whereHas('folder', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            }
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('word', 'like', "%{$search}%")
                    ->orWhere('translation', 'like', "%{$search}%")
                    ->orWhere('example_sentence', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $words = $query->paginate($request->get('per_page', 20));

        return $this->successResponse(
            'Words retrieved successfully.',
            WordResource::collection($words)
        );
    }


    /**
     * Store new word
     */
    public function store(StoreWordRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('audio_file')) {
            $data['audio_url'] = $request->file('audio_file')->store('audio', 'public');
        }

        $word = Word::create($data);

        return $this->successResponse('Word created successfully.', new WordResource($word), 201);
    }

    /**
     * Show a word
     */
    public function show(Request $request, Word $word): JsonResponse
    {
        $user = $request->user();

        if ($word->folder->user_id !== $user->id) {
            return 1;
        }

        return $this->successResponse(
            'Word retrieved successfully.',
            new WordResource($word),
            200
        );
    }


    /**
     * Update word
     */
    public function update(UpdateWordRequest $request, Word $word): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('audio_file')) {
            if ($word->audio_url) {
                $oldPath = str_replace(asset('storage/'), '', $word->audio_url);
                Storage::disk('public')->delete($oldPath);
            }
            $data['audio_url'] = $request->file('audio_file')->store('audio', 'public');
        }

        $word->update($data);

        return $this->successResponse('Word updated successfully.', new WordResource($word));
    }

    /**
     * Delete word
     */
    public function destroy(Word $word): JsonResponse
    {
        if ($word->audio_url) {
            $oldPath = str_replace(asset('storage/'), '', $word->audio_url);
            Storage::disk('public')->delete($oldPath);
        }

        $word->delete();

        return $this->successResponse('Word deleted successfully.');
    }
}
