<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Folder;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'word',
        'translation',
        'example_sentence',
        'audio_url',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function getAudioUrlAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
        return null;
    }
}
