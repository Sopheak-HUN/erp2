<?php

namespace App\Models\Accounting;

use App\Models\Concerns\BelongsToTenant;
use App\Models\Concerns\HasUuidV7;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PostingPeriod extends Model
{
    use BelongsToTenant, HasUuidV7, LogsActivity;

    protected $fillable = [
        'year',
        'month',
        'start_date',
        'end_date',
        'status', // open, soft_closed, hard_closed
        'closed_by_user_id',
        'closed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'closed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
