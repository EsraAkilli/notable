<?php

namespace App\Services;

use App\Http\Resources\NoteResource;
use App\Models\Note;

class NoteService
{
    private string $title;

    private string $content;

    private mixed $user;

    private Note $note;


    public static function make(): self
    {
        return new self();
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function create(): Note
    {
        $this->note = Note::query()->create([
            'title' => $this->title,
            'content' => $this->content,
            'user_id' => $this->user,
        ]);

        return $this->note;
    }

    public function resource(): NoteResource
    {
        return NoteResource::make($this->note);
    }
}
