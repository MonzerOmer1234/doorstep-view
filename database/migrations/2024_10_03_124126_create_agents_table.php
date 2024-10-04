<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name'); // Agent's name
            $table->string('email')->unique(); // Unique email
            $table->string('phone')->nullable(); // Optional phone number
            $table->text('bio')->nullable(); // Short biography
            $table->string('profile_picture')->nullable(); // URL/path to profile picture
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('agents');
    }
}
