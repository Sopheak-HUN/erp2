<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->string('sku')->index();
            $table->string('name_en');
            $table->string('name_km')->nullable();
            $table->text('description')->nullable();
            $table->uuid('category_id')->nullable();
            $table->string('unit_of_measure')->default('unit');
            $table->bigInteger('cost_price')->default(0);
            $table->bigInteger('selling_price')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->boolean('vat_applicable')->default(true);
            $table->boolean('is_service')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('reorder_level')->default(0);
            $table->integer('current_stock')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'sku']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
