<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Note
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 *
 * @method static Builder|Note authorize(?\App\Models\User $user = null)  // What are these?
 * @method static Builder|Note newModelQuery()
 * @method static Builder|Note newQuery()
 * @method static Builder|Note query()
 *
 * @mixin \Eloquent
 */
class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAuthorize(Builder $query, User $user = null): void
    {
        $user ??= user();

        $query->where('user_id', $user->id);
    }
}
