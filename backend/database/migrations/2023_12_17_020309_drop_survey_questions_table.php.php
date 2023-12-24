<?php

use App\Models\Survey;
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
      Schema::dropIfExists('survey_questions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::create('survey_questions', function (Blueprint $table) {
          $table->id();
          $table->foreignIdFor(Survey::class, 'survey_id');
          $table->json('questions');
      });
    }
};
