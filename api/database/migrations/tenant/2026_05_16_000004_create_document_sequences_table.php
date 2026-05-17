<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_sequences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->string('document_type');
            $table->integer('fiscal_year');
            $table->string('prefix')->nullable();
            $table->unsignedBigInteger('last_number')->default(0);
            $table->timestamps();

            $table->unique(['tenant_id', 'document_type', 'fiscal_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_sequences');
    }
};
