<?php 

namespace App\Scripts;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SurveyMethods {

  public static function createQuestion($data) {

    if(is_array($data['selectionChoices'])) {
      $data['selectionChoices'] = 
      json_encode($data['selectionChoices']);
    }

    $validator = self::makeSurveyQuestionValidator($data);

    return SurveyQuestion::create($validator->validated());
  }

  public static function updateQuestion(SurveyQuestion $question, $mappedQuestion) {
    if(is_array($mappedQuestion['selectionChoices'])) {
      $mappedQuestion['selectionChoices'] = 
      json_encode($mappedQuestion['selectionChoices']);
    }

    $validator = self::makeSurveyQuestionValidator($mappedQuestion);

    return $question->update($validator->validated());
  }

  public static function encodeImageToBase64($imgPath) {
    $file = file_get_contents($imgPath);
    $fileExt = pathinfo($imgPath, PATHINFO_EXTENSION);
    $base64 = 'data:image/' . $fileExt . ';base64,' . base64_encode($file);
    return $base64;
  }

  static function makeSurveyQuestionValidator($data) {
    return Validator::make($data, [
      'survey_id' => 'exists:App\Models\Survey,id',
      'title' => 'required|string',
      'type' => ['required', Rule::in([
        Survey::TYPE_TEXT,
        Survey::TYPE_SELECTION,
      ])],
      'selectionChoices' => 'present'
    ]);
  }
}

?>