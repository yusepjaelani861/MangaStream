<?php

use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return Inertia::render('Public/Home');
})->name('home');

// Route::get('/manga/{slug}', function ($slug) {
//     return Inertia::render('Public/Manga', [
//         'slug' => $slug
//     ]);
// });

Route::get('/manga/{slug}', [PublicController::class, 'manga'])->name('manga');

// Route::get('/manga/{slug}/{chapter}', function ($slug, $chapter) {
//     return Inertia::render('Public/Chapter', [
//         'slug' => $slug,
//         'chapter' => $chapter
//     ]);
// });

Route::get('/manga/{slug}/{chapter}', [PublicController::class, 'chapter'])->name('chapter');

Route::get('/image/{mangadex_id}/{filename}', [ImagesController::class, 'get']);
Route::get('/data-saver/{base_url}/data-saver/{hash}/{filename}', [ImagesController::class, 'getChapterImages']);

Route::get('/search', function (Request $request) {
    return Inertia::render('Public/Search', [
        'title' => $request->title,
    ]);
})->name('search');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
