<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyAnswerRequest;
use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Http\Resources\SurveyResource;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyAnswerQuestion;
use App\Models\SurveyQuestion;
use App\Scripts\ImageMethods;
use App\Scripts\SurveyMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $user = $request->user();
        return SurveyResource::collection(Survey::where('user_id', $user->id)->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     * Note: add payload fields to the rules of StoreSurveyRequest so 
     * that payload can be passed here.
     */
    public function store(StoreSurveyRequest $request) {
        $data = $request->validated();

        if(isset($data['image'])) {
          $relativePath = ImageMethods::saveImage($data['image']);
          $data['image'] = $relativePath;
        }

        //Create survey first before creating question
        $result = Survey::create($data);

        foreach($data['questions'] as $question) {
          $question['survey_id'] = $result->id;
          SurveyMethods::createQuestion($question);
        }

        return new SurveyResource($result);
        
    }

    /*
      Custom function. Not part of the built-in function of a resource
      controller.

      Displays a single survey based on its slug
    */
    public function showPublic(Survey $survey) {
      return new SurveyResource($survey);
    }

    public function getPublishedSurveys(Request $request) {
      $public_surveys = Survey::where([
        'status' => 1
      ]);

      return response($public_surveys, 200);
    }

    /*
      Custom function. Not part of the built-in function of a resource
      controller 
    */
    public function storeAnswer(StoreSurveyAnswerRequest $request, Survey $survey) {
      $validated = $request->validated();
      $user = $request->user();
      $surveyAnswer = SurveyAnswer::create([
        'survey_id' => $survey->id,
        'user_id' => $user->id,
        /*
          This should be anwer_date only. For
          some reason I created two dates with
          same values.
        */
        'start_date' => date('Y-m-d H:i:s'),
        'end_date' => date('Y-m-d H:i:s')
      ]);
      
      foreach($validated['answers'] as $questionId => $answer) {
        /*
          use get() to fetch data in the database. where only
          produces the query.
        */
        $question = SurveyQuestion::where(
          [
            'id' => $questionId, 
            'survey_id' => $survey->id
          ]
        )->get();

        if(!$question) {
          return response('Invalid Request', 400);
        }

        $data = [
          'survey_question_id' => $questionId,
          'survey_answer_id' => $surveyAnswer->id,
          'answer' => is_array($answer) ? json_encode($answer) : $answer
        ];
        SurveyAnswerQuestion::create($data);
      }

      return response('Survey has been completed', 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Survey $survey, Request $request) {
        $user = $request->user();
        if($user->id !== $survey->user_id) {
          return abort(403, 'Forbidden Resource');
        }
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     * Note: add payload fields to the rules of UpdateSurveyRequest so 
     * that payload can be passed here.
     */
    public function update(UpdateSurveyRequest $request, Survey $survey) {
        $data = $request->validated();

        if(isset($data['image'])) {
          $relativePath = ImageMethods::saveImage($data['image']);
          $data['image'] = $relativePath;

          //If there's a non-null value in image
          //column in the database
          if($survey->image) {
            $absolutePath = public_path($survey->image);
            File::delete($absolutePath);
          }
        }
      
        //Update survey based on the fillable columns in
        //the model
        $survey->update($data);

        //get existing question ids before adding new questions
        $existingIds = Arr::pluck($survey->questions, 'id');
        $updatedQuestionIds = array();
        
        //Loop through payload questions
        foreach($data['questions'] as $question) {
          /* 
            id from frontend that is 0 or less than 0 means that
            the question is new. 
          */
          if($question['id'] <= 0) {
            $question['survey_id'] = $survey->id;
            //Create new questions
            SurveyMethods::createQuestion($question);
          }
        }

        //Loop through survey questions
        $questionCollection = collect($data['questions'])->keyBy('id');
        foreach($survey->questions as $question) {
          if(isset($questionCollection[$question->id])) {
            SurveyMethods::updateQuestion(
              $question, 
              $questionCollection[$question->id]
            );
            array_push($updatedQuestionIds, $question->id);
          }
        }

        //Get Ids from $existingIds that don't exist in 
        //$updatedQuestionIds
        $removedQuestions = array_diff($existingIds, $updatedQuestionIds);

        //Delete questions that are removed by user.
        SurveyQuestion::destroy($removedQuestions);
        
        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey, Request $request) {
      $user = $request->user();
      if($user->id !== $survey->id) {
        return abort(403, 'Forbidden Resource');
      }
      
      $survey->delete();

      if($survey->image) {
        $absolutePath = public_path($survey->image);
        File::delete($absolutePath);
      }

      return response('', 204);
    }

    
}
