<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\StatisticController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v1/articles', [ArticleController::class, 'showArticles']);
Route::get('/v1/article/{slug}', [ArticleController::class, 'showBySlug']);
Route::get('/v1/article/{slug}/comments', [ArticleController::class, 'showComments']);
Route::post('/v1/article/{slug}/comment', [ArticleController::class, 'postComment']);
Route::get('/v1/categories', [ArticleController::class, 'showCategories']);
Route::get('/v1/tags', [ArticleController::class, 'showTags']);

Route::prefix('v1')->group(function () {
    Route::post('/statistics', [StatisticController::class, 'store']);
    Route::get('/statistics', [StatisticController::class, 'index']);
});