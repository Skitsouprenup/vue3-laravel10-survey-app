<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SurveyForDashboardResource;

class SurveyAnswerForDashboard extends JsonResource
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
          'survey' => new SurveyForDashboardResource($this->survey),
          'end_date' => $this->end_date
        ];
    }
}
