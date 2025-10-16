<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'name_en'           => $this->name_en,
            'description'    => $this->description,
            'description_en'    => $this->description_en,
            'price'          => $this->price,
            'duration_months'=> $this->duration_months,
            'words_limit'    => $this->words_limit,
            'subscriptions' => $this->subscriptions()->count(),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
