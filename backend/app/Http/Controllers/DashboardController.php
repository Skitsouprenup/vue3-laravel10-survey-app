<?php

namespace App\Http\Controllers;

use App\Http\Resources\SurveyAnswerForDashboard;
use App\Http\Resources\SurveyForDashboardResource;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request) {
      $user = $request->user();

      /*
        Alternative:
        $total = Survey::query()->where('user_id', $user->id)->count();

        This alternative is useful when you're building query
        with various possible outcome
      */
      $totalSurveys = Survey::where('user_id', $user->id)->count();
      $latestSurvey = Survey::where('user_id', $user->id)
        ->latest('created_at')
        ->first();

      $totalAnswers = SurveyAnswer::join(
          'surveys', 
          'survey_answers.survey_id', '=', 'surveys.id'
        )
        ->where('surveys.user_id', $user->id)
        ->count();

      $latestAnswers = SurveyAnswer::join(
        'surveys', 
        'survey_answers.survey_id', '=', 'surveys.id'
      )
      ->where('surveys.user_id', $user->id)
      ->orderBy('end_date', 'DESC')
      ->limit(5)->get();

      return [
        'totalSurveys' => $totalSurveys,
        'latestSurvey' => $latestSurvey 
        ? new SurveyForDashboardResource($latestSurvey) 
        : null,
        'totalAnswers' => $totalAnswers,
        'latestAnswers' => SurveyAnswerForDashboard::collection($latestAnswers)
      ];
    }
}
