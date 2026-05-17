<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->string('invoice_number')->index();
            $table->enum('invoice_type', ['sales', 'purchase']);
            $table->enum('document_type', ['tax_invoice', 'credit_note', 'debit_note']);
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->uuid('contact_id')->nullable();
            $table->string('contact_vatin')->nullable();
            $table->string('contact_name');
            $table->string('contact_name_km')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('exchange_rate_nbc', 12, 6)->default(1);
            $table->bigInteger('subtotal')->default(0);
            $table->bigInteger('vat_amount')->default(0);
            $table->bigInteger('wht_amount')->default(0);
            $table->bigInteger('total')->default(0);
            $table->bigInteger('subtotal_khr')->default(0);
            $table->bigInteger('vat_amount_khr')->default(0);
            $table->bigInteger('total_khr')->default(0);
            $table->enum('status', ['draft', 'issued', 'paid', 'void'])->default('draft');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->string('void_reason')->nullable();
            $table->uuid('journal_entry_id')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'invoice_number']);
        });

        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->uuid('invoice_id');
            $table->uuid('product_id')->nullable();
            $table->string('description');
            $table->string('description_km')->nullable();
            $table->integer('quantity');
            $table->bigInteger('unit_price')->default(0);
            $table->bigInteger('line_total')->default(0);
            $table->string('tax_code')->nullable();
            $table->bigInteger('tax_amount')->default(0);
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
    }
};
