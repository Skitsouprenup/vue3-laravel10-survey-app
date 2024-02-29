<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserCredentialsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
  The first argument is the url endpoint, the next one is an array and
  its first element is a fully qualified class name and the second element
  is the name of a method that is endpoint is going to execute. The method
  must be a member of the full qualified class name.
*/
Route::post('/register', [UserCredentialsController::class, 'register']);
Route::post('/login', [UserCredentialsController::class, 'login']);
/*
  This parameter is called implicit model or model binding.
  the ':slug' is the column name tha laravel will select in order to
  resolve model binding. If no column specified, laravel will select
  the 'id' column by default.
*/
Route::get('/survey/slug/{survey:slug}', [SurveyController::class, 'showPublic']);

Route::middleware('auth:sanctum')->group(function() {
  Route::post('/survey/{survey}/answer', [SurveyController::class, 'storeAnswer']);
  Route::get('/survey/published', [SurveyController::class, 'getPublishedSurveys']);

  /*Route to quick-check if user is authenticated */
  Route::get('/user', function (Request $request) {
    return $request->user();
  });
  Route::post('/logout', [UserCredentialsController::class, 'logout']);
  Route::resource('/survey', SurveyController::class);
  Route::get('/dashboard', [DashboardController::class, 'index']);

  //Route::get('/answer/latest', [AnswerController::class, 'index']);
  //This comes before '/answer/{surveyid}' because laravel can't distinguish
  //between '/answered' and '/{surveyid}' since both of them are considered
  //as string. Thus, when '/answer/{surveyid}' is on top and then we visit
  //'/answer/answered', laravel executes '/answer/{surveyid}' endpoint.
  Route::get('/answer/answered', [AnswerController::class, 'checkIfAnsweredByUser']);
  Route::get('/answer/{surveyid}', [AnswerController::class, 'getAnswersBySurveyId']);
});
