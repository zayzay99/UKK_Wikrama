<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'officer_id',
        'borrow_date',
        'return_date',
        'actual_return_date',
        'status',
        'fine_reason',
        'fine_days',
        'fine_rate',
        'fine_amount',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'return_date' => 'date',
            'actual_return_date' => 'date',
            'fine_rate' => 'decimal:2',
            'fine_amount' => 'decimal:2',
        ];
    }

    protected $appends = [
        'is_overdue',
        'overdue_days',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->status !== 'dipinjam') {
            return false;
        }

        return $this->return_date !== null && $this->return_date->isPast();
    }

    public function getOverdueDaysAttribute(): int
    {
        if (! $this->is_overdue) {
            return 0;
        }

        return max(1, $this->return_date->diffInDays(now()));
    }
}
