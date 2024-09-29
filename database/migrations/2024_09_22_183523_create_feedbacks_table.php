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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            // 'user_id',
            // 'apartment_id',
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->index();
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade')->index();
            $table->string('rating');
            $table->string('comment') ;
            // 'rating' ,
            // 'comment',
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
