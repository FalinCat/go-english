<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = ['translation', 'transcription'];

    public function languages(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Language::class)->withPivot('translation', 'transcription', 'image_id');
    }

    public function getTranslationAttribute($value)
    {
        return strtolower($value);
    }

    public function getTranscriptionAttribute($value)
    {
        return strtolower($value);
    }
}
