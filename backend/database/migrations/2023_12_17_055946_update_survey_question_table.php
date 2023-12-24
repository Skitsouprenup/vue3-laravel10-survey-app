<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Survey;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('survey_questions', function (Blueprint $table) {
        $table->id();
        $table->foreignIdFor(Survey::class, 'survey_id');
        $table->string('title', 255);
        $table->string('type', 200);
        $table->json('selectionChoices');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('survey_questions');
    }
};
