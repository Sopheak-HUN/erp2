<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->string('entry_number')->index();
            $table->date('entry_date');
            $table->uuid('posting_period_id');
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('exchange_rate', 12, 6)->default(1);
            $table->enum('status', ['draft', 'posted', 'void'])->default('draft');
            $table->timestamp('posted_at')->nullable();
            $table->uuid('posted_by_user_id')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->uuid('voided_by_user_id')->nullable();
            $table->string('void_reason')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'entry_number']);
            $table->foreign('posting_period_id')->references('id')->on('posting_periods');
        });

        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->uuid('journal_entry_id');
            $table->uuid('account_id');
            $table->bigInteger('debit_amount')->default(0);
            $table->bigInteger('credit_amount')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->bigInteger('debit_amount_khr')->default(0);
            $table->bigInteger('credit_amount_khr')->default(0);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('journal_entry_id')->references('id')->on('journal_entries')->cascadeOnDelete();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->index(['tenant_id', 'account_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entry_lines');
        Schema::dropIfExists('journal_entries');
    }
};
