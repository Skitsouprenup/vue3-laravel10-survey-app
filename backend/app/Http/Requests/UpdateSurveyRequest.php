<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //get the survey model in route handler. This is
        //the $survey from update() function for SurveyController
        //because this class is associated with the function.
        $survey = $this->route('survey');

        if($this->user()->id !== $survey->user_id) {
          return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
        'title' => 'required|string|max:1000',
        'image' => 'nullable|string',
        'user_id' => 'exists:user,id',
        'status' => 'required|boolean',
        'description' => 'nullable|string',
        'expire_date' => 'nullable|date|after:tomorrow',
        'questions' => 'array'
      ];
    }
}
