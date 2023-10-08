<?php

namespace App\Http\Requests\NoteRequests;

class DestroyNoteRequest
{
    public function rules(): array
    {
        return [
            'note' => ['exists:notes,id'],
        ];
    }
}
