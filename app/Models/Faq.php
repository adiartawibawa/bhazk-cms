<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasFactory, HasUuids, HasTranslations;

    protected $fillable = [
        'question',
        'answer',
        'is_published'
    ];

    public array $translatable = [
        'question',
        'answer',
    ];
}
