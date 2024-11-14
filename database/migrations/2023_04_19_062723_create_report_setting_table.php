<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_setting', function (Blueprint $table) {
            $table->id();
            $table->string('email_notification')->default('0');
            $table->string('slack_notification')->default('0');
            $table->string('is_daily')->default('0');
            $table->string('is_weekly')->default('0');
            $table->string('is_monthly')->default('0');
            $table->string('created_by')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_setting');
    }
};
