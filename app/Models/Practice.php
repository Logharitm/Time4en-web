<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Practice extends Model
{
    use HasFactory;

    /**
     * @var array
     */

    protected $fillable = [
        'user_id',
        'folder_id',
        'total_words',
        'correct_answers',
        'wrong_answers',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }


    public function answers(): HasMany
    {
        return $this->hasMany(PracticeAnswer::class);
    }
}
