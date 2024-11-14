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
        Schema::create('site', function (Blueprint $table) {
            $table->id();
            $table->string('account_id')->nullable();
            $table->string('site_name')->nullable();
            $table->string('property_id')->nullable();
            $table->string('property_name')->nullable();
            $table->string('view_id')->nullable();
            $table->string('view_name')->nullable();
            $table->string('accessToken')->nullable();
            $table->string('refreshToken')->nullable();
            $table->string('timeframe')->default('30daysAgo');
            $table->string('graph')->nullable()->default('activeUsers');
            $table->string('graph_type')->nullable()->default('bar');
            $table->string('graph_color')->nullable()->default('#172b4d');
            $table->string('top_left')->nullable()->default('activeUsers');
            $table->string('top_right')->nullable()->default('newUsers');
            $table->string('bottom_left')->nullable()->default('bounceRate');
            $table->string('bottom_right')->nullable()->default('sessions');
            $table->string('created_by')->default(0);
            $table->integer('is_active')->default(0);
            $table->string('share_setting',5000)->nullable();
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
        Schema::dropIfExists('site');
    }
};
