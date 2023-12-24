<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
  Note: This migration renames the table
  instead of renaming a table column
*/

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::table('state_date', function(Blueprint $table) {
        $table->rename('survey_answers');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('survey_answers', function(Blueprint $table) {
        $table->rename('state_date');
      });
    }
};
