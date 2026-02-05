<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'caller_name' => $this->caller_name,
            'phone_number' => $this->phone_number,
            'source' => $this->source->value,
            'transcript' => $this->transcript,
            'converted' => $this->converted,
            'conversion_reason' => $this->conversion_reason,
            'duration_seconds' => $this->duration_seconds,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
