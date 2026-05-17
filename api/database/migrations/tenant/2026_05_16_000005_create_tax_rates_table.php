<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->string('tax_type');
            $table->string('code');
            $table->string('name_en');
            $table->string('name_km');
            $table->decimal('rate', 8, 4);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->string('residency')->nullable();
            $table->string('payment_category')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();

            $table->index(['tenant_id', 'tax_type', 'effective_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_rates');
    }
};
