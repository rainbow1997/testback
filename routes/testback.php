<?php

/*
|--------------------------------------------------------------------------
| Jamali\Testback Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Jamali\Testback package.
|
*/

/**
 * User Routes
 */

 Route::group([
     'middleware'=> array_merge(
     	(array) config('backpack.base.web_middleware', 'web'),
     ),
 ], function() {
     Route::post('category-search', [\Rainbow1997\Testback\Http\Controllers\ArticleController::class,'categorySearch'])->name('categorySearch');
 });


/**
 * Admin Routes
 */

 Route::group([
     'prefix' => config('backpack.base.route_prefix', 'admin'),
     'middleware' => array_merge(
         (array) config('backpack.base.web_middleware', 'web'),
         (array) config('backpack.base.middleware_key', 'admin')
     ),
 ], function () {
     Route::crud('article', \Rainbow1997\Testback\Http\Controllers\ArticleCrudController::class);
     Route::crud('category', \Rainbow1997\Testback\Http\Controllers\CategoryCrudController::class);
 });
