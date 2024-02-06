<?php

use App\Http\Controllers\Admin\BlogsController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\CommentsController;
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

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => ['auth']], function () {
        /* Categories routes admin section */
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [CategoriesController::class, 'index'])->name('admin.categories.index');
            Route::get('/create', [CategoriesController::class, 'create'])->name('admin.categories.create');
            Route::post('/store', [CategoriesController::class, 'store'])->name('admin.categories.store');
            Route::get('/{id}/edit', [CategoriesController::class, 'edit'])->name('admin.categories.edit');
            Route::post('/update', [CategoriesController::class, 'update'])->name('admin.categories.update');
            Route::get('/{id}/delete', [CategoriesController::class, 'delete'])->name('admin.categories.delete');
        });
        /* Articles routes admin section */
        Route::group(['prefix' => 'blogs'], function () {
            Route::get('/', [BlogsController::class, 'index'])->name('admin.blogs.index');
            Route::get('/create', [BlogsController::class, 'create'])->name('admin.blogs.create');
            Route::post('/store', [BlogsController::class, 'store'])->name('admin.blogs.store');
            Route::get('/{id}/edit', [BlogsController::class, 'edit'])->name('admin.blogs.edit');
            Route::post('/update', [BlogsController::class, 'update'])->name('admin.blogs.update');
            Route::get('/{id}/delete', [BlogsController::class, 'delete'])->name('admin.blogs.delete');
        });

        /* Comments routes admin section */
        Route::group(['prefix' => 'comments'], function () {
            Route::get('/{id}/{blog_id}/delete', [CommentsController::class, 'delete'])->name('admin.comments.delete');
        });
    });

});