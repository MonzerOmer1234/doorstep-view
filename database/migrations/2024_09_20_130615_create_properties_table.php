<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->default(1);

            $table->string('title'); // Title of the property
            $table->text('description'); // Description of the property
            $table->decimal('price', 10, 2); // Price of the property
            $table->string('location'); // Location of the property
            $table->integer('bedrooms')->nullable(); // Number of bedrooms
            $table->integer('bathrooms')->nullable(); // Number of bathrooms
            $table->integer('area')->nullable(); // Area in square feet
            $table->string('property_type'); // e.g., apartment, villa
            $table->string('status')->default('available'); // e.g., available, sold
            
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('neighborhood')->nullable();
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
