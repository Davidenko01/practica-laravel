<?php

declare(strict_types=1);

namespace App\Models;

use App\IdeaState;
use Database\Factories\IdeaFactory;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Idea extends Model
{
    /** @use HasFactory<IdeaFactory> */
    use HasFactory;

    protected $fillable = [
        'state' => IdeaState::PENDING->value,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    public static function statusCount(User $user): Collection
    {
        $counts = $user->ideas()
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state');

        return collect(IdeaState::cases())
            ->mapWithKeys(fn (IdeaState $state) => [
                $state->value => $counts->get($state->value, 0),
            ])
            ->put('all', $counts->sum());
    }

    /**
     * The public URL of the idea image, if any.
     *
     * @return Attribute<?string, never>
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->image_path
            ? asset('storage/'.$this->image_path)
            : null);
    }

    protected function casts(): array
    {
        return [
            'links' => AsArrayObject::class,
            'state' => IdeaState::class,
        ];
    }
}
