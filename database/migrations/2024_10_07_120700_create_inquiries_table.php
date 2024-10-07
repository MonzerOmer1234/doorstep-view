<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id'); // Foreign key to the properties table
            $table->string('name');  // The name of the person making the inquiry
            $table->string('email'); // Contact email
            $table->string('phone')->nullable(); // Contact phone (optional)
            $table->text('message'); // The inquiry message
            $table->timestamp('created_at')->useCurrent(); // Timestamp for when the inquiry was made
            $table->timestamp('updated_at')->useCurrent()->nullable(); // Timestamp for updates

            // Set up foreign key constraint
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiries');
    }
}
