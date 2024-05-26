<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => ['sometimes', 'max:255'],
            'is_public' => ['sometimes', 'boolean'],
        ];
    }
}
