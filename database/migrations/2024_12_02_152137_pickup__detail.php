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
        Schema::create('pickup_detail', function (Blueprint $table) {
            $table->id('pickup_detail_id');
            $table->foreignId('pickup_id')->constrained('pickup_waste', 'pickup_id')->onDelete('cascade');
            $table->foreignId('waste_id')->constrained('waste', 'waste_id')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
