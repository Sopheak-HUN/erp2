<?php

namespace App\Models\Tax;

use App\Models\Concerns\BelongsToTenant;
use App\Models\Concerns\HasUuidV7;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
    use BelongsToTenant, HasUuidV7, SoftDeletes, LogsActivity;

    protected $fillable = [
        'invoice_number',
        'invoice_type',     // sales, purchase
        'document_type',    // tax_invoice, credit_note, debit_note
        'invoice_date',
        'due_date',
        'contact_id',
        'contact_vatin',
        'contact_name',
        'contact_name_km',
        'currency',
        'exchange_rate_nbc',
        'subtotal',          // minor units
        'vat_amount',        // minor units
        'wht_amount',        // minor units
        'total',             // minor units
        'subtotal_khr',
        'vat_amount_khr',
        'total_khr',
        'status',           // draft, issued, paid, void
        'issued_at',
        'voided_at',
        'void_reason',
        'journal_entry_id',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'exchange_rate_nbc' => 'decimal:6',
        'subtotal' => 'integer',
        'vat_amount' => 'integer',
        'wht_amount' => 'integer',
        'total' => 'integer',
        'subtotal_khr' => 'integer',
        'vat_amount_khr' => 'integer',
        'total_khr' => 'integer',
        'issued_at' => 'datetime',
        'voided_at' => 'datetime',
    ];

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
