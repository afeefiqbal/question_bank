<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [App\Http\Controllers\QuestionController::class, 'index'])->name('welcome');
Route::post('/questions/save', [App\Http\Controllers\QuestionController::class, 'save'])->name('questions.save');
Route::post('/questions/view', [App\Http\Controllers\QuestionController::class, 'view'])->name('questions.view');
