<?php

namespace App\Models\Payroll;

use App\Models\Concerns\BelongsToTenant;
use App\Models\Concerns\HasUuidV7;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use BelongsToTenant, HasUuidV7, SoftDeletes;

    protected $fillable = [
        'employee_id',       // internal employee number
        'first_name_en',
        'last_name_en',
        'first_name_km',
        'last_name_km',
        'gender',
        'date_of_birth',
        'national_id',
        'nssf_member_id',
        'vatin',
        'contract_type',     // udc (undetermined duration), fdc (fixed duration)
        'hire_date',
        'termination_date',
        'department',
        'position',
        'base_salary',       // minor units
        'salary_currency',
        'dependents',        // number of dependents for ToS allowance
        'is_resident',       // for tax purposes
        'bank_account',
        'bank_name',
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'base_salary' => 'integer',
        'dependents' => 'integer',
        'is_resident' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }
}
