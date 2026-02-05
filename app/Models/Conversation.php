<?php

namespace App\Models;

use App\Enums\ConversationSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'caller_name',
        'phone_number',
        'source',
        'transcript',
        'converted',
        'conversion_reason',
        'duration_seconds',
    ];

    protected function casts(): array
    {
        return [
            'source' => ConversationSource::class,
            'converted' => 'boolean',
            'duration_seconds' => 'integer',
        ];
    }
}
