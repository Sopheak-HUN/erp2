<?php

namespace App\Models\Accounting;

use App\Models\Concerns\BelongsToTenant;
use App\Models\Concerns\HasUuidV7;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JournalEntry extends Model
{
    use BelongsToTenant, HasUuidV7, SoftDeletes, LogsActivity;

    protected $fillable = [
        'entry_number',
        'entry_date',
        'posting_period_id',
        'reference',
        'description',
        'currency',
        'exchange_rate',
        'status', // draft, posted, void
        'posted_at',
        'posted_by_user_id',
        'voided_at',
        'voided_by_user_id',
        'void_reason',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'exchange_rate' => 'decimal:6',
        'posted_at' => 'datetime',
        'voided_at' => 'datetime',
    ];

    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function postingPeriod()
    {
        return $this->belongsTo(PostingPeriod::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
