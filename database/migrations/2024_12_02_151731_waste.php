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
        Schema::create('waste', function (Blueprint $table) {
            $table->id('waste_id');
            $table->string('waste_name');
            $table->integer('point');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('waste_type_id')->constrained('waste_type', 'waste_type_id')->onDelete('cascade');
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
