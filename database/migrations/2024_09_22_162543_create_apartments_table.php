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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('address');
            $table->boolean('available')->default(true);
            $table->integer('rooms'); // Number of rooms
            $table->decimal('area');   // Area of the apartment
            $table->integer('building_age'); // Age of the building
            $table->timestamps();

            // Add indexes for frequently queried columns
            $table->index('title');
            $table->index('price');
            $table->index('available');
            $table->index('rooms');
            $table->index('area');
            $table->index('building_age');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
