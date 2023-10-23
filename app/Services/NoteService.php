<?php

namespace App\Services;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\User;

class NoteService
{
    private string $title;

    private string $content;

    private User $user;

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

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setUser(User|\Illuminate\Contracts\Auth\Authenticatable $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setNote(Note $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function create(): Note
    {
        $this->note = Note::query()->create([
            'title' => $this->title,
            'content' => $this->content,
            'user_id' => $this->user->id,
        ]);

        return $this->note;
    }

    public function update(): Note
    {
        $this->note->update([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        return $this->note;
    }

    public function destroy(): bool
    {
        return $this->note->delete();
    }

    public function resource(): NoteResource
    {
        return NoteResource::make($this->note);
    }
}
