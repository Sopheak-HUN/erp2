<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DocumentSequenceService
{
    /**
     * Get the next sequential number for a document type.
     * Uses row-level locking to prevent race conditions.
     */
    public function nextNumber(string $tenantId, string $documentType, int $fiscalYear, ?string $prefix = null): string
    {
        return DB::transaction(function () use ($tenantId, $documentType, $fiscalYear, $prefix) {
            $sequence = DB::table('document_sequences')
                ->where('tenant_id', $tenantId)
                ->where('document_type', $documentType)
                ->where('fiscal_year', $fiscalYear)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                $nextNumber = 1;
                DB::table('document_sequences')->insert([
                    'id' => \Illuminate\Support\Str::uuid7()->toString(),
                    'tenant_id' => $tenantId,
                    'document_type' => $documentType,
                    'fiscal_year' => $fiscalYear,
                    'prefix' => $prefix,
                    'last_number' => $nextNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $nextNumber = $sequence->last_number + 1;
                DB::table('document_sequences')
                    ->where('id', $sequence->id)
                    ->update([
                        'last_number' => $nextNumber,
                        'updated_at' => now(),
                    ]);
            }

            $effectivePrefix = $prefix ?? strtoupper(substr($documentType, 0, 3));

            return sprintf('%s-%d-%06d', $effectivePrefix, $fiscalYear, $nextNumber);
        });
    }
}
