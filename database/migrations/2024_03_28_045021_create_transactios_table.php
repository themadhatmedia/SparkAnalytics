<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->string('referrance_code')->nullable();
                $table->string('used_referrance')->nullable();
                $table->string('company_name')->nullable();
                $table->string('plan_name')->nullable();
                $table->string('plan_price')->nullable();
                $table->string('plan_commission_rate')->nullable();
                $table->string('threshold_amount')->nullable();
                $table->integer('commission')->nullable();
                $table->integer('uid')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
