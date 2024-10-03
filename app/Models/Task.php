<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'status' => TaskStatus::class,
    ];

    protected $fillable = [
        'title',
        'description',
        'status',
        'reporter_id',
        'assignee_id',
    ];

    /**
     * Get the task counts grouped by statuses.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getTaskCounts()
    {
        $taskCountsByStatuses = ['total' => static::count()];

        foreach (TaskStatus::cases() as $status) {
            $taskCountsByStatuses[$status->value] = static::countByStatus($status->value);
        }

        return collect($taskCountsByStatuses);
    }

    /**
     * Get the count of tasks with the given status.
     *
     * @param TaskStatus|string $status
     *
     * @return int
     */
    public static function countByStatus($status)
    {
        return static::where('status', $status)->count();
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeFilter($query, array $filters) {
        if ($filters['search'] ?? false) {
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%');
        }
    }
}
