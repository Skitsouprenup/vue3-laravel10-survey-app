<?php

namespace App\Http\Resources;

use App\Scripts\SurveyMethods;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /* This is the response payload */
        return [
          'id' => $this->id,
          /* prepend image path with backend URL */
          'imagePrev' => $this->image ? URL::to($this->image) : null,
          'imageAsset' => $this->image ? 
            SurveyMethods::encodeImageToBase64($this->image) : null,
          'title' => $this->title,
          'slug' => $this->slug,
          'status' => $this->status,
          'description' => $this->description,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
          'expire_date' => $this->expire_date,
          'questions' => SurveyQuestionResource::collection($this->questions)
        ];
    }
}
