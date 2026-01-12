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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->text('description')->required();
            $table->string('slug')->unique();
            $table->foreignId('state_id')->constrained('auction_states');
            $table->foreignId('city_id')->constrained('auction_cities');
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('sq_ft')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
