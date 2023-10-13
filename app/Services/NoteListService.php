<?php

namespace App\Services;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class NoteListService
{
    private User $user;

    private array $filters = [];

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function make(User $user): self
    {
        return new self($user);
    }

    public function addFilter(string $column, string|null $content): self
    {
        if (empty($content)) {
            return $this;
        }

        $this->filters[] = [
            'column' => $column,
            'value' => $content,
        ];

        return $this;
    }

    protected function getQuery(): Builder
    {
        $query = Note::query()->authorize($this->user);

        foreach ($this->filters as $filter) {
            $value = $filter['value'];
            $query->where($filter['column'], 'LIKE', "%$value%");
        }

        return $query;
    }

    public function get(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $notes = $this->getQuery()
            ->orderBy('updated_at', 'DESC')
            ->get();

        return NoteResource::collection($notes);
    }
}
