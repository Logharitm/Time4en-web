<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PracticeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'user_id'         => $this->user_id,
            'user_name'       => optional($this->user)->name,
            'folder_id'       => $this->folder_id,
            'folder_name'     => optional($this->folder)->name,
            'total_words'     => $this->total_words,
            'correct_answers' => $this->correct_answers,
            'wrong_answers'   => $this->wrong_answers,
            'score_percentage'=> $this->total_words > 0
                ? round(($this->correct_answers / $this->total_words) * 100, 2)
                : 0,
            'created_at'      => $this->created_at?->toDateTimeString(),
        ];
    }
}
