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
        Schema::create('feedback', function (Blueprint $table) {

            // 'user_id',
            // 'apartment_id',
            $table->id(); // Primary key
            $table->foreignId('user_id')
                ->constrained('users') // Specify users table
                ->onDelete('cascade')
                ->index()
                ->name('fk_feedback_user'); // Explicit foreign key name
                $table->foreignId('apartment_id')
                ->constrained('apartments') // Specify apartments table
                ->onDelete('cascade')
                ->index()
                ->name('fk_feedback_apartment'); // Explicit foreign key name
            $table->string('rating');
            $table->string('comment');
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
