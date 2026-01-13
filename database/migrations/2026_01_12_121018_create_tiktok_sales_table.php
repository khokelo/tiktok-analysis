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
        Schema::create('tiktok_sales', function (Blueprint $table) {
            $table->id();
            $table->string('campaign')->nullable();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->decimal('direct_gmv', 15, 2)->default(0);
            $table->integer('items_sold')->default(0);
            $table->integer('customers')->default(0);
            $table->integer('viewers')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiktok_sales');
    }
};
