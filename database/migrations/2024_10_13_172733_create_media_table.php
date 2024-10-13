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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id'); // Reference to the message
            $table->string('media_type'); // Type of media (e.g., 'image', 'video')
            $table->string('media_path'); // Path to the uploaded media file
            $table->timestamps();

            // Foreign key for message relationship
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
