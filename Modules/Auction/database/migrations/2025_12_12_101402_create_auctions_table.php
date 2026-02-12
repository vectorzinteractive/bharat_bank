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
            $table->string('slug');
            $table->foreignId('pincode_id')->constrained('pincode');
            $table->decimal('price', 18, 2);
            $table->decimal('sq_ft', 8, 2)->nullable();
            $table->timestamps();
            $table->unique(['slug']);
            $table->index('pincode_id');
            $table->index('price');
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
