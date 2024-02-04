<?php

use App\Http\Controllers\Admin\CategoriesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\Site\BlogsController;
use App\Http\Controllers\Site\CommentsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider
|
*/
include base_path('routes/admin.php');

Route::get('/', [BlogsController::class, 'homeBlogs'])->name('site.pages.homepage');
Route::get('/{title}/{id}/', [BlogsController::class, 'homeBlog'])->name('site.pages.blog');
Route::get('/{categoryName}/{id}/', [CategoriesController::class, 'getCategoriesBlogsPage'])->name('site.pages.categories');


Route::controller(LoginRegisterController::class)->group(function () {
    // Register a new user
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    // Login the user
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::post('/logout', 'logout')->name('logout');
});

Route::group(['prefix' => 'comments'], function () {
    Route::post('/store', [CommentsController::class, 'store'])->name('site.comments.store');
});