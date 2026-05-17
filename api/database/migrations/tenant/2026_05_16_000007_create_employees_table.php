<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->string('employee_id')->index();
            $table->string('first_name_en');
            $table->string('last_name_en');
            $table->string('first_name_km')->nullable();
            $table->string('last_name_km')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth')->nullable();
            $table->string('national_id')->nullable();
            $table->string('nssf_member_id')->nullable();
            $table->string('vatin')->nullable();
            $table->enum('contract_type', ['udc', 'fdc'])->default('udc');
            $table->date('hire_date');
            $table->date('termination_date')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->bigInteger('base_salary');
            $table->string('salary_currency', 3)->default('USD');
            $table->integer('dependents')->default(0);
            $table->boolean('is_resident')->default(true);
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'employee_id']);
        });

        Schema::create('payslips', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->uuid('employee_id');
            $table->integer('year');
            $table->integer('month');
            $table->bigInteger('base_salary');
            $table->bigInteger('allowances')->default(0);
            $table->bigInteger('overtime')->default(0);
            $table->bigInteger('gross_salary');
            $table->bigInteger('nssf_employee')->default(0);
            $table->bigInteger('nssf_employer')->default(0);
            $table->bigInteger('taxable_salary');
            $table->bigInteger('tax_on_salary')->default(0);
            $table->bigInteger('other_deductions')->default(0);
            $table->bigInteger('net_salary');
            $table->bigInteger('seniority_indemnity')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->decimal('exchange_rate', 12, 6)->default(1);
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->timestamps();

            $table->unique(['tenant_id', 'employee_id', 'year', 'month']);
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payslips');
        Schema::dropIfExists('employees');
    }
};
