<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewCountToPropertiesTable extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('view_count')->default(0); // Add the view count column
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('view_count'); // Remove the view count column
        });
    }
}
