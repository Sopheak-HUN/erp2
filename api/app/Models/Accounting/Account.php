<?php

namespace App\Models\Accounting;

use App\Models\Concerns\BelongsToTenant;
use App\Models\Concerns\HasUuidV7;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use BelongsToTenant, HasUuidV7, SoftDeletes;

    protected $fillable = [
        'code',
        'name_en',
        'name_km',
        'parent_id',
        'type', // Asset, Liability, Equity, Revenue, Expense
        'is_tax_account',
        'is_postable',
        'is_active',
    ];

    protected $casts = [
        'is_tax_account' => 'boolean',
        'is_postable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
