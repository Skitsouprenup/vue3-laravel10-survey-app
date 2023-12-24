<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class SurveyForDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        'id' => $this->id,
        /* prepend image path with backend URL */
        'imagePrev' => $this->image ? URL::to($this->image) : null,
        'title' => $this->title,
        'slug' => $this->slug,
        'status' => $this->status,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'expire_date' => $this->expire_date,
        'questions' => $this->questions()->count(),
        'answers' => $this->answers()->count()
      ];
    }
}
