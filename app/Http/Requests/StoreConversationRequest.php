<?php

namespace App\Http\Requests;

use App\Enums\ConversationSource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'caller_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'source' => ['required', Rule::enum(ConversationSource::class)],
            'transcript' => ['required', 'string'],
            'converted' => ['sometimes', 'boolean'],
            'conversion_reason' => ['nullable', 'string', 'max:255'],
            'duration_seconds' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
