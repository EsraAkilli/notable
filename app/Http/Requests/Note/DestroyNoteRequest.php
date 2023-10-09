<?php

namespace App\Http\Requests\Note;

use App\Models\Note;

class DestroyNoteRequest
{
    public function rules(): array
    {
        return [
            'note' => ['exists:notes,id'],
        ];
    }
}
