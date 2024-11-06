<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('visit_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id'); // Foreign key to properties table
            $table->unsignedBigInteger('user_id');     // Foreign key to users table
            $table->dateTime('requested_at');
            $table->string('visitor_name');
            $table->string('visitor_email');
            $table->dateTime('visit_date');
            $table->string('status')->default('pending'); // Status can be 'pending', 'approved', 'rejected'
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('visit_requests');
    }
}
