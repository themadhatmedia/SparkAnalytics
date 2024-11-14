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
        Schema::create('aletr', function (Blueprint $table) {
            $table->id();
            $table->string('site_id')->nullable();
            $table->string('metric')->nullable();
            $table->string('dimension')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('duration')->nullable();
            $table->string('created_by')->nullable();
            $table->string('email_notification')->nullable();
            $table->string('slack_notification')->nullable();
            
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
        Schema::dropIfExists('aletr');
    }
};
