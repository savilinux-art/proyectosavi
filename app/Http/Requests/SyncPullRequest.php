<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncPullRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'since' => ['nullable', 'date_format:Y-m-d\TH:i:s\Z'],
        ];
    }

    public function messages(): array
    {
        return [
            'since.date_format' => 'El campo since debe estar en ISO 8601 UTC (Y-m-d\TH:i:s\Z).',
        ];
    }
}
