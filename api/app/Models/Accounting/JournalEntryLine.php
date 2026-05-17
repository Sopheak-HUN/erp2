<?php

namespace App\Models\Accounting;

use App\Models\Concerns\BelongsToTenant;
use App\Models\Concerns\HasUuidV7;
use Illuminate\Database\Eloquent\Model;

class JournalEntryLine extends Model
{
    use BelongsToTenant, HasUuidV7;

    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'debit_amount',
        'credit_amount',
        'currency',
        'debit_amount_khr',
        'credit_amount_khr',
        'description',
    ];

    protected $casts = [
        'debit_amount' => 'integer',    // minor units
        'credit_amount' => 'integer',   // minor units
        'debit_amount_khr' => 'integer',
        'credit_amount_khr' => 'integer',
    ];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
