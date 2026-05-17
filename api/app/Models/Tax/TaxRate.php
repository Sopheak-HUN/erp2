<?php

namespace App\Models\Tax;

use App\Models\Concerns\BelongsToTenant;
use App\Models\Concerns\HasUuidV7;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use BelongsToTenant, HasUuidV7;

    protected $fillable = [
        'tax_type',        // vat, wht, tos, cit, nssf_or, nssf_hc, nssf_pension
        'code',
        'name_en',
        'name_km',
        'rate',            // decimal percentage (e.g., 10.00 for 10%)
        'effective_from',
        'effective_to',
        'residency',       // resident, non_resident, null (for non-WHT)
        'payment_category', // for WHT: services, royalties, interest, rent, etc.
        'is_system',       // true = seeded by system, false = user-created
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_system' => 'boolean',
    ];

    /**
     * Get the applicable rate for a given date.
     */
    public static function applicableRate(string $taxType, string $date, ?string $residency = null): ?self
    {
        return static::where('tax_type', $taxType)
            ->where('effective_from', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', $date);
            })
            ->when($residency, fn ($q) => $q->where('residency', $residency))
            ->orderByDesc('effective_from')
            ->first();
    }
}
