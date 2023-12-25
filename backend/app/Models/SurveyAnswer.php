<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;

    /* 
      Alternative way to disable created_at and updated_at
      columns.

      Note: Doesn't work on laravel 10, you'll get this hasAttribute error:
      Too few arguments to function ... and exactly 2 expected
    */
    //const CREATED_AT = false;
    //const UPDATED_AT = false;

    public $timestamps = false;

    protected $fillable = ['survey_id', 'user_id', 'start_date', 'end_date'];

    public function survey() {
      return $this->belongsTo(Survey::class);
    }
}
