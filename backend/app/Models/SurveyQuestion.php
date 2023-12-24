<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    /* 
      Disables created_at and updated_at columns. Eloquent
      expects timestamps even the timestamps are not declared
      in the migration. 
    */
    public $timestamps = false;

    protected $fillable = ['survey_id', 'title', 'type', 'selectionChoices'];

    protected $casts = [
      'selectionChoices' => 'array'
    ];

    public function survey() {
      return $this->belongsTo(Survey::class);
    }
}
