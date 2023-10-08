<?php

namespace App\Http\Requests\NoteRequests;

class ShowNoteRequest
{
    public function rules(): array
    {
        return [
            'note' => ['exists:notes,id'],
        ];
    }
}
