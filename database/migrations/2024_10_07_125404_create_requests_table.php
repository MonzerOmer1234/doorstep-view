<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Assuming requests are made by users
            $table->unsignedBigInteger('property_id'); // The property the request is for
            $table->text('message'); // The request message or inquiry
            $table->string('status')->default('pending'); // Status of the request (pending, approved, rejected)
            $table->timestamps();

            // Foreign key constraints (optional, if you have related tables)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
