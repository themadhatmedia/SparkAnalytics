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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->unique();
            $table->float('monthly_price')->default(0);
            $table->float('annual_price')->default(0);
            $table->string('duration')->nullable();
            $table->integer('max_site')->default(0);
            $table->integer('max_widget')->default(0);
            $table->integer('max_user')->default(0);
            $table->string('additional_page')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('status')->default(0);
            $table->integer('custom')->comments('0,1')->default(0);
            $table->integer('analytics')->comments('0,1')->default(0);
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
        Schema::dropIfExists('plan');
    }
};
