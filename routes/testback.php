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

// Route::group([
//     'middleware'=> array_merge(
//     	(array) config('backpack.base.web_middleware', 'web'),
//     ),
// ], function() {
//     Route::get('something/action', \Jamali\Testback\Http\Controllers\SomethingController::actionName());
// });


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
//     Route::crud('some-entity-name', \Jamali\Testback\Http\Controllers\Admin\EntityNameCrudController::class);
     Route::crud('article', \Jamali\Testback\Http\Controllers\ArticleCrudController::class);
     Route::crud('category', \Jamali\Testback\Http\Controllers\CategoryCrudController::class);
 });
