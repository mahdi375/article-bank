<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SourceController;
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

// TODO: in real app we should consider api versioning....
// TODO: Versioning changes dir structure of: routes, controllers, resources, requests

Route::prefix('articles')->name('articles.')->controller(ArticleController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('{article}', 'show')->name('show');
});

Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index');
});

Route::prefix('authors')->name('authors.')->controller(AuthorController::class)->group(function () {
    Route::get('/', 'index');
});

Route::prefix('sources')->name('sources.')->controller(SourceController::class)->group(function () {
    Route::get('/', 'index');
});
