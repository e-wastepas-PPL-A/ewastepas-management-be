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
        Schema::create('pickup_waste', function (Blueprint $table) {
            $table->id('pickup_id');
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->string('pickup_address');
            $table->enum('pickup_status', ['requested', 'accepted', 'completed', 'cancelled']);
            $table->string('dropbox_id');
            $table->foreignId('courier_id')->nullable()->constrained('management', 'management_id')->onDelete('set null');
            $table->foreignId('community_id')->nullable()->constrained('management', 'management_id')->onDelete('set null');
            $table->foreignId('management_id')->constrained('management', 'management_id')->onDelete('cascade');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_wastes');
    }
};
