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
        Schema::create('waste_type', function (Blueprint $table) {
            $table->id('waste_type_id');
            $table->enum('waste_type_name', ['Peralatan rumah tangga besar', 'Peralatan rumah tangga kecil', 'Peralatan IT', 'Lampu', 'Mainan', 'Peralatan elektronik lainnya']);
            $table->string('image')->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_types');
    }
};
