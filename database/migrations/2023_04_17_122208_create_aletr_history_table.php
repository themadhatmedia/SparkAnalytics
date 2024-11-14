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
        Schema::create('aletr_history', function (Blueprint $table) {
            $table->id();
            $table->string('aletr_id')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('site_id')->nullable();
            $table->string('created_by')->nullable();
            
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
        Schema::dropIfExists('aletr_history');

    }
};
