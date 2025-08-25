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
            ? Word::query()
            : Word::whereHas('folder', fn($q) => $q->where('user_id', $user->id));

        if ($request->filled('folder_id')) {
            $query->where('folder_id', $request->folder_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q
                ->where('word', 'like', "%{$search}%")
                ->orWhere('translation', 'like', "%{$search}%")
                ->orWhere('example_sentence', 'like', "%{$search}%")
            );
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
        dd($this->generate($request->word));

        $data = $request->validated();

        if ($request->hasFile('audio_file')) {
            $data['audio_url'] = $request->file('audio_file')->store('audio', 'public');
        }

        $word = Word::create($data);

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
        $word = trim($word);

        // رسالة الطلب (prompt) لتوليد جملة قصيرة
        $prompt = "Write a short meaningful sentence using the word: {$word}.";

        // استدعاء API
        $response = Http::withToken(env('HUGGINGFACE_API_KEY'))
            ->timeout(60)
            ->post('https://api-inference.huggingface.co/models/gpt2', [
                'inputs' => $prompt,
                'parameters' => [
                    'max_new_tokens' => 20,   // عدد الكلمات الجديدة
                    'temperature' => 0.7,     // عشوائية (0 = ثابت, 1 = عشوائي)
                ]
            ]);
        if ($response->successful()) {
            $result = $response->json();

            // Hugging Face بيرجع Array
            $sentence = $result[0]['generated_text'] ?? "Here is a sentence with {$word}.";
        } else {
            $sentence = "Here is a sentence with {$word}.";
        }

        return $sentence;
    }
}
