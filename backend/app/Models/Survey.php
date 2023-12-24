<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    use HasSlug;

    const TYPE_TEXT = 'text';
    const TYPE_SELECTION = 'selection';

    protected $fillable = [
      'user_id', 'title', 'slug', 'status', 'description',
      'expire_date', 'image'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
      return SlugOptions::create()
          ->generateSlugsFrom('title')
          ->saveSlugsTo('slug');
    }

    public function questions() {
      return $this->hasMany(SurveyQuestion::class);
    }

    public function answers() {
      return $this->hasMany(SurveyAnswer::class);
    }
}
