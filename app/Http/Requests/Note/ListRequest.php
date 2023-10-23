<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'max:255'],
            'content' => ['sometimes', 'max:255'],
        ];
    }
}
