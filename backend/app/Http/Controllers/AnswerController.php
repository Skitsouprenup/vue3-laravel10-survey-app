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

    public function getAnswersBySurveyId(Request $request, string $surveyid) {
      
      //I'm using mariaDB database and it doesn't support FULL JOIN or
      //FULL OUTER JOIN keyword. Thus, the solution here is to use UNION

      $answersSurvey = 
        'SELECT survey_answers.id, survey_questions.title '.
        'FROM survey_answers '.
        'LEFT JOIN survey_questions ON '.
        'survey_answers.survey_id = survey_questions.survey_id '.
        'WHERE survey_answers.survey_id = '.$surveyid.
        ' UNION ';

      $questionsSurvey = 
        'SELECT survey_answers.id, survey_questions.title '.
        'FROM survey_answers '.
        'RIGHT JOIN survey_questions ON '.
        'survey_answers.survey_id = survey_questions.survey_id '.
        'WHERE survey_answers.survey_id = '.$surveyid;

      $dbQuery = 
        $answersSurvey.$questionsSurvey;
      
      $data = DB::select($dbQuery);
      $answerId = '';
      $titles = [];
      
      //select() returns stdClass object.
      //use '->' operator to access stdClass
      //properties or we can loop class
      //like this
      foreach($data as $x => $y) {
        foreach($y as $z => $a) {
          if($z === 'id') {
            $answerId = $a;
          }

          if($z === 'title') {
            array_push($titles, $a);
          }
        }
      }
      
      $dbQuery = 
        'SELECT answer FROM survey_answer_questions '.
        'WHERE survey_answer_id='.$answerId;
      $answers = DB::select($dbQuery);
      
      $answerList = [];

      foreach($answers as $x => $y) {
        foreach($y as $z => $a) {
          if($z === 'answer') {
            array_push($answerList,$a);
          }
        }
      }

      $users = 
        'SELECT users.name, surveys.title FROM surveys '.
        'INNER JOIN users ON users.id = surveys.user_id '.
        'WHERE surveys.id ='.$surveyid;
      $data = DB::select($users);

      $response = [];
      $response['surveyTitle'] = $data[0]->title;
      $response['name'] = $data[0]->name;
      $response['titles'] = $titles;
      $response['answers'] = $answerList;

      return response($response, 200);
    }
}
