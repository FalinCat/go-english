<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\WordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('words', WordController::class)->middleware('auth:sanctum');

Route::get('/languages', [LanguageController::class, 'index']);
Route::get('/languages/{id}', [LanguageController::class, 'show']);

Route::get('/images', [ImageController::class, 'index']);
Route::get('/images/{id}', [ImageController::class, 'show']);


//Route::apiResource('english-words', EnglishWordController::class)->middleware('auth:sanctum');
//Route::apiResource('russian-words', RussianWordController::class)->middleware('auth:sanctum');



/*
Route::get('/english-words', [EnglishDictionaryController::class, 'index']);
Route::get('/english-words/{englishDictionary}', [EnglishDictionaryController::class, 'show']);
Route::post('/english-words', [EnglishDictionaryController::class, 'store']);
*/

/*
Route::get('/words', [DictionaryController::class, 'getAllWords']);
Route::get('/words/{id}', [DictionaryController::class, 'getWord']);
Route::post('/word', [DictionaryController::class, 'addWord']);
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
