<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'note' => ['exists:notes,id'],
            'title' => ['required', 'max:255'],
            'content' => ['required'],
        ];
    }
}
