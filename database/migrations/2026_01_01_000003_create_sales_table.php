<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->string('campaign')->nullable();
        $table->string('day');
        $table->date('date');
        $table->time('time');
        $table->decimal('direct_gmv', 15, 2)->default(0);
        $table->integer('items_sold')->default(0);
        $table->integer('customers')->default(0);
        $table->integer('sku_orders')->default(0);
        $table->integer('main_orders')->default(0);
        $table->integer('viewers')->default(0);
        $table->integer('views')->default(0);
        $table->integer('product_impressions')->default(0);
        $table->decimal('click_through_rate', 15, 3)->default(0);
        $table->decimal('enter_room_rate', 15, 3)->default(0);
        $table->integer('product_clicks')->default(0);
        $table->integer('impressions')->default(0);
        $table->integer('new_followers')->default(0);
        $table->integer('shares')->default(0);
        $table->integer('comments')->default(0);
        $table->integer('likes')->default(0);
        $table->timestamps();
    });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};