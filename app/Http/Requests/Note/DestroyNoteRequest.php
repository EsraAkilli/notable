<?php

namespace App\Http\Requests\Note;

class DestroyNoteRequest
{
    public function rules(): array
    {
        return [
            'note' => ['exists:notes,id'],
        ];
    }
}
