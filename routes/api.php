<?php

use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;

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
//route login
Route::post('/login', [LoginController::class, 'index']);

//group route with middleware "auth"
Route::group(['middleware' => 'auth:api'], function () {

    //logout
    Route::post('/logout', [LoginController::class, 'logout']);
});

//group route with prefix "admin"
Route::prefix('admin')->group(function () {
    //group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function () {
        //dashboard
        Route::get('/dashboard', DashboardController::class);

        //permissions
        Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:permissions.index');
        Route::get('/permissions/all', [PermissionController::class, 'all'])->middleware('permission:permissions.index');

        //Roles

        Route::get('/roles/all', [RoleController::class, 'index'])->middleware('permission:roles.index');
        Route::apiResource('/roles', RoleController::class)
            ->middleware('permission:roles.index|roles.store|roles.update|roles.delete');

        //users
        Route::apiResource('/users', App\Http\Controllers\Api\Admin\UserController::class)
            ->middleware('permission:users.index|users.store|users.update|users.delete');

        //categories all
        Route::get('/categories/all', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'all'])
            ->middleware('permission:categories.index');

        //Categories
        Route::apiResource('/categories', App\Http\Controllers\Api\Admin\CategoryController::class)
            ->middleware('permission:categories.index|categories.store|categories.update|categories.delete');
    });
});


//group route with prefix "public"
Route::prefix('public')->group(function () {

    //index posts
    Route::get('/posts', [App\Http\Controllers\Api\Public\PostController::class, 'index']);

    //show posts
    Route::get('/posts/{slug}', [App\Http\Controllers\Api\Public\PostController::class, 'show']);

    //index posts home
    Route::get('/posts_home', [App\Http\Controllers\Api\Public\PostController::class, 'homePage']);

    //index products
    Route::get('/products', [App\Http\Controllers\Api\Public\ProductController::class, 'index']);

    //show page
    Route::get('/products/{slug}', [App\Http\Controllers\Api\Public\ProductController::class, 'show']);

    //index products home
    Route::get('/products_home', [App\Http\Controllers\Api\Public\ProductController::class, 'homePage']);

    //index pages
    Route::get('/pages', [App\Http\Controllers\Api\Public\PageController::class, 'index']);

    //show page
    Route::get('/pages/{slug}', [App\Http\Controllers\Api\Public\PageController::class, 'show']);

    //index aparaturs
    Route::get('/aparaturs', [App\Http\Controllers\Api\Public\AparaturController::class, 'index']);

    //index photos
    Route::get('/photos', [App\Http\Controllers\Api\Public\PhotoController::class, 'index']);

    //index sliders
    Route::get('/sliders', [App\Http\Controllers\Api\Public\SliderController::class, 'index']);
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
