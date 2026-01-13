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
        Schema::create('auction_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'state_id')->constrained('auction_states')->onDelete('cascade');
            $table->string('name', 150)->unique();
            $table->string('slug')->unique();
            $table->foreignId('pincode_id')->constrained('pincode')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_cities');
    }
};
