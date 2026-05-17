<?php

namespace App\Models\Tenant;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'name_km',
            'vatin',
            'taxpayer_classification',
            'functional_currency',
            'fiscal_year_start',
            'nssf_employer_id',
            'is_active',
        ];
    }

    protected $casts = [
        'is_active' => 'boolean',
        'data' => 'array',
    ];
}
