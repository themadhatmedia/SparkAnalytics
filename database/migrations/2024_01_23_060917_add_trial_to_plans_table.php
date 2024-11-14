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
        if (!Schema::hasColumn('plans', 'trial')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->integer('trial')->default(0)->after('analytics');
                $table->string('trial_days')->nullable()->after('trial');
                $table->decimal('monthly_price', 30, 2)->nullable()->default(0.0)->change();
                $table->decimal('annual_price', 30, 2)->nullable()->default(0.0)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            //
        });
    }
};
