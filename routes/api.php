<?php

use App\Http\Controllers\APIs\Mangadex\MangaController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/manga', [MangaController::class, 'getList']);
Route::get('/manga/{mangadex_id}', [MangaController::class, 'getDetail']);
Route::get('/chapter', [MangaController::class, 'getChapter']);
Route::get('/server/{chapter}', [MangaController::class, 'getImages']);
// Route::get('/')
