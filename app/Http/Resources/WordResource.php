<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WordResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'folder_id' => $this->folder_id,
            'word' => $this->word,
            'translation' => $this->translation,
            'example_sentence' => $this->example_sentence,
            'audio_url' => $this->audio_url,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
