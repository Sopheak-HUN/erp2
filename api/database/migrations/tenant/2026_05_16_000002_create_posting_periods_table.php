<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posting_periods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->integer('year');
            $table->integer('month');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['open', 'soft_closed', 'hard_closed'])->default('open');
            $table->uuid('closed_by_user_id')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posting_periods');
    }
};
