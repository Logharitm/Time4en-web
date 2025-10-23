<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexWordRequest;
use App\Http\Requests\WordShowRequest;
use App\Models\Word;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWordRequest;
use App\Http\Requests\UpdateWordRequest;
use App\Http\Resources\WordResource;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class WordController extends Controller
{
    use ApiResponse;

    /**
     * List all words in a folder with search and filter
     */
    public function index(IndexWordRequest $request): JsonResponse
    {
        $user = $request->user();

        $query = $user->role === 'admin'
            ? Word::query()->with(['folder.user'])
            : Word::whereHas('folder', fn($q) => $q->where('user_id', $user->id))
                ->with(['folder.user']);

        if ($request->filled('folder_id')) {
            $query->where('folder_id', $request->folder_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('word', 'like', "%{$search}%")
                    ->orWhere('translation', 'like', "%{$search}%")
                    ->orWhere('example_sentence', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_name')) {
            $query->whereHas('folder.user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->user_name}%");
            });
        }

        if ($request->filled('folder_name')) {
            $query->whereHas('folder', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->folder_name}%");
            });
        }

        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $query->orderBy(
            $request->get('sort_by', 'created_at'),
            $request->get('sort_order', 'desc')
        );

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

        $sentence = $this->generate($data['word']);

        $data['example_sentence'] = $sentence;

        $word = Word::create($data);

        // Return the success response with the newly created Word
        return $this->successResponse('Word created successfully.', new WordResource($word), 200);
    }

    /**
     * Show a word
     */
    public function show(WordShowRequest $request, Word $word)
    {
        $user = $request->user();
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
        // return $this->successResponse('W', ['Request' => $data, 'Word' =>$word]);

        if ($request->hasFile('audio_file')) {
            if ($word->audio_url) {
                $oldPath = str_replace(asset('storage/'), '', $word->audio_url);
                Storage::disk('public')->delete($oldPath);
            }

            $data['audio_url'] = $request->file('audio_file')->store('audio', 'public');
        }else{
            if( $data->audio_url == 'audio_url'){
                $data['audio_url'] = NULL;

            }
        }

        $sentence = $this->generate($data['word']);

        $data['example_sentence'] = $sentence;

        $word->update($data);

        return $this->successResponse('Word updated successfully.', new WordResource($word));
    }


    /**
     * Delete word
     */
    public function destroy(WordShowRequest $request,Word $word): JsonResponse
    {

        if ($word->audio_url) {
            $oldPath = str_replace(asset('storage/'), '', $word->audio_url);
            Storage::disk('public')->delete($oldPath);
        }

        $word->delete();

        return $this->successResponse('Word deleted successfully.');
    }

    public function generate($word): mixed
    {
        // Trim the input word to remove any leading/trailing whitespace
        $word = trim($word);

        // The base URL for the new API
        $apiUrl = "https://api.dictionaryapi.dev/api/v2/entries/en/{$word}";

        // Make a GET request to the new API
        $response = Http::timeout(60)->get($apiUrl);

        $sentence = "Here is a sentence with {$word}."; // Default sentence in case of failure

        if ($response->successful()) {
            $data = $response->json();

            // The API returns an array of objects. We'll take the first one.
            if (!empty($data) && isset($data[0]['meanings'])) {
                foreach ($data[0]['meanings'] as $meaning) {
                    // Look for a definition that contains an example sentence
                    foreach ($meaning['definitions'] as $definition) {
                        if (isset($definition['example'])) {
                            $sentence = $definition['example'];
                            return $sentence; // Found an example, so return it immediately
                        }
                    }
                }
            }
        }

        return $sentence;
    }
}
