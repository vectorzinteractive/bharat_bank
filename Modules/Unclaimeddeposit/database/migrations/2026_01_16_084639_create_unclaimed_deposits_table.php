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
        Schema::create('unclaimed_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->string('slug');
            $table->integer('udrn_id')->required();
            $table->text('description')->required();
            $table->foreignId('pincode_id')->constrained('pincode');
            $table->timestamps();
            $table->unique(['name','slug','udrn_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unclaimed_deposits');
    }
};
