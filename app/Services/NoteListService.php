<?php

namespace App\Services;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class NoteListService
{
    private User|null $user;

    private array $filters = [];

    private int $perPage = 100;
    private ?string $searchQuery = null;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public static function make(User $user = null): self
    {
        return new self($user);
    }

    public function perPage(?int $value): self
    {
        if ($value) {
            $this->perPage = $value;
        }

        return $this;
    }

    public function addFilter(string $column, ?string $content): self
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
        $query = Note::query();

        if ($this->user) {
            $query->authorize($this->user);
        }

        foreach ($this->filters as $filter) {
            $value = $filter['value'];

            $query->where($filter['column'], 'LIKE', "%$value%");
        }

        if ($this->searchQuery) {
            $query->where(function ($query) {
                $searchString = "%$this->searchQuery%";

               return $query->where('title', 'LIKE', $searchString)
                   ->orWhere('content', 'LIKE', $searchString)
                   ->orWhere('tags_for_search', 'LIKE', $searchString);
            });
        }

        return $query;
    }

    public function search(?string $query): self
    {
        $this->searchQuery = $query;

        return $this;
    }

    public function result(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        /** @var \Illuminate\Pagination\AbstractPaginator $notes */
        $notes = $this->getQuery()
            ->orderBy('updated_at', 'DESC')
            ->simplePaginate($this->perPage);

        return NoteResource::collection(
            $notes
        );
    }
}
