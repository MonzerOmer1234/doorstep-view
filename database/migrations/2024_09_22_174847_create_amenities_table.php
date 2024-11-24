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
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            
            $table->string('category')->default('school'); // e.g., school, supermarket, hospital
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->default(10);
            $table->decimal('longitude', 10, 7)->default(10); // Geographical coordinates
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
