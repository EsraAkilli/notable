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

    private int $perPage = 10;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function make(User $user): self
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

    /* public function addTagFilter(string $name, ?string $value): self
    {
        if (empty($value)) {
            return $this;
        }

        $this->filters[] = [
            'relation' => 'tags',
            'column' => 'tags',
            'operator' => 'LIKE',
            'value' => $value,
        ];

        return $this;
    } */

    protected function getQuery(): Builder
    {
        $query = Note::query()->authorize($this->user);

        foreach ($this->filters as $filter) {
            $value = $filter['value'];

            $query->where($filter['column'], 'LIKE', "%$value%");
        }
        return $query;
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
