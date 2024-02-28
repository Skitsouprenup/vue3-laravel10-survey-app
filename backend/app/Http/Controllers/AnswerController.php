<?php

namespace App\Http\Controllers;

use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
    public function checkIfAnsweredByUser(Request $request) {
      $surveyId = '';
      $user = $request->user();

      if($request->has('surveyid')) {
        $surveyId = $request->query('surveyid');
      } else {
        return response('Bad Request', 400);
      }

      $answer = SurveyAnswer::where([
        'user_id' => $user->id,
        'survey_id' => $surveyId
      ])->get();

      if($answer && count($answer) > 0) {
        return response(['answered' => true], 200);
      } else {
        return response(['answered' => false], 200);
      }
    }

    public function index(Request $request) {
      return DB::table('survey_answer_questions')->get();
    }

    public function getAnswersBySurveyId(Request $request) {
      $surveyId = '';

      if($request->has('surveyid')) {
        $userId = $request->query('surveyid');
      } else {
        return response('Bad Request', 400);
      }

      $dbQuery = 
        'SELECT * FROM survey_answer_questions '.
        'WHERE survey_id='.$surveyId;

      $answers = DB::select($dbQuery);

      return response($answers, 200);
    }
}
