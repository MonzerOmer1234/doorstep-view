<?php

// database/migrations/2024_10_03_000001_add_deleted_at_to_properties_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToPropertiesTable extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->softDeletes(); // Adds the deleted_at column
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Drops the deleted_at column
        });
    }
}
