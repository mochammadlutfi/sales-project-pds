<?php

use Illuminate\Http\Request;
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

Route::namespace('App\Http\Controllers')->group(function(){
    
    Route::prefix('/base')->name('base.')->group(function () {
        Route::get('/', 'BaseController@index')->name('index');
        Route::get('/set-lang/{lang}','BaseController@setLang');
    });
    
    Route::post('/login', 'AuthController@login')->name("login");

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', 'AuthController@logout')->name("logout");

        Route::prefix('/profile')->name('profile.')->group(function () {
            Route::get('/', 'ProfileController@index')->name('index');
            Route::post('/update','ProfileController@update')->name('update');
            Route::post('/password','ProfileController@password')->name('password');
            Route::get('/device', 'ProfileController@device')->name('device');
            Route::post('/device/disconect','ProfileController@deviceDisconnect')->name('device.disconect');

        });

        Route::prefix('/sales')->name('sales.')->group(function () {
            Route::get('/', 'SalesController@index')->name('index');
            Route::post('/store','SalesController@store')->name('store');
            Route::get('/{id}', 'SalesController@show')->name('show');
            Route::post('/{id}/update','SalesController@update')->name('update');
            Route::delete('/{id}/delete','SalesController@destroy')->name('delete');
        });

        Route::prefix('/project')->name('project.')->group(function () {
            Route::get('/', 'ProjectController@index')->name('index');
            Route::post('/store','ProjectController@store')->name('store');
            Route::get('/{id}', 'ProjectController@show')->name('show');
            Route::post('/{id}/update','ProjectController@update')->name('update');
            Route::delete('/{id}/delete','ProjectController@destroy')->name('delete');
            Route::post('/{id}/done','ProjectController@done')->name('done');
        });

        Route::prefix('/activity')->name('activity.')->group(function () {
            Route::get('/', 'ProjectActivityController@index')->name('index');
            Route::post('/store','ProjectActivityController@store')->name('store');
            Route::get('/{id}', 'ProjectActivityController@show')->name('show');
            Route::get('/{id}/activity', 'ProjectActivityController@activity')->name('activity');
            Route::post('/{id}/update','ProjectActivityController@update')->name('update');
            Route::delete('/{id}/delete','ProjectActivityController@destroy')->name('delete');
        });

        Route::namespace('Settings')->prefix('/settings')->group(function () {

            Route::prefix('/user')->name('user.')->group(function () {
                Route::get('/', 'UserController@index')->name('index');
                Route::post('/store','UserController@store')->name('store');
                Route::get('/{id}', 'UserController@show')->name('show');
                Route::post('/{id}/update','UserController@update')->name('update');
                Route::delete('/{id}/delete','UserController@destroy')->name('delete');
            });

            
            Route::prefix('/permissions')->name('permissions.')->group(function () {
                Route::get('/', 'PermissionController@index')->name('index');
                Route::get('/list', 'PermissionController@list')->name('list');
                Route::post('/store','PermissionController@store')->name('store');
                Route::get('/{id}', 'PermissionController@show')->name('show');
                Route::post('/{id}/update','PermissionController@update')->name('update');
                Route::delete('/{id}/delete','PermissionController@destroy')->name('delete');
            });

            Route::prefix('/language')->name('language.')->group(function () {
                Route::get('/', 'LanguageController@index')->name('index');
                Route::post('/store','LanguageController@store')->name('store');
                Route::get('/{id}', 'LanguageController@show')->name('show');
                Route::post('/{id}/update','LanguageController@update')->name('update');
                Route::delete('/{id}/delete','LanguageController@destroy')->name('delete');
            });

            
            Route::prefix('/branch')->name('branch.')->group(function () {
                Route::get('/', 'BranchController@index')->name('index');
                Route::post('/store','BranchController@store')->name('store');
                Route::get('/{id}', 'BranchController@show')->name('show');
                Route::post('/{id}/update','BranchController@update')->name('update');
                Route::delete('/{id}/delete','BranchController@destroy')->name('delete');
            });

            
            Route::prefix('/general')->name('general.')->group(function () {
                Route::get('/', 'SystemController@general');
                Route::post('/update','SystemController@generalUpdate');
            });
            
            Route::prefix('/email')->name('email.')->group(function () {
                Route::get('/', 'SystemController@email');
                Route::post('/update','SystemController@emailUpdate');
            });
        });
    });
});


Route::namespace('App\Http\Controllers\Sales')->prefix('/salesman')->group(function(){
    
    Route::post('/login', 'AuthController@login')->name("login");

    Route::get('/branch', 'BaseController@branch')->name("branch");
    Route::get('/sales', 'BaseController@sales')->name("sales");

    Route::get('/testing', function () {
        $data = 'sasad';
        return response()->json($data);
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {


        Route::post('/logout', 'AuthController@logout')->name("logout");

        Route::prefix('/profile')->name('profile.')->group(function () {
            Route::get('/', 'ProfileController@index')->name('index');
            Route::post('/update','ProfileController@update')->name('update');
            Route::post('/password','ProfileController@password')->name('password');
            Route::get('/device', 'ProfileController@device')->name('device');
            Route::post('/device/disconect','ProfileController@deviceDisconnect')->name('device.disconect');

        });

        Route::prefix('/project')->name('project.')->group(function () {
            Route::get('/', 'ProjectController@index')->name('index');
            Route::post('/store','ProjectController@store')->name('store');
            Route::get('/{id}', 'ProjectController@show')->name('show');
            Route::get('/{id}/activity', 'ProjectController@activity')->name('activity');
            Route::post('/{id}/update','ProjectController@update')->name('update');
            Route::delete('/{id}/delete','ProjectController@destroy')->name('delete');
        });

        Route::prefix('/activity')->name('activity.')->group(function () {
            Route::get('/', 'ProjectActivityController@index')->name('index');
            Route::post('/store','ProjectActivityController@store')->name('store');
            Route::get('/{id}', 'ProjectActivityController@show')->name('show');
            Route::get('/{id}/activity', 'ProjectActivityController@activity')->name('activity');
            Route::post('/{id}/update','ProjectActivityController@update')->name('update');
            Route::delete('/{id}/delete','ProjectActivityController@destroy')->name('delete');
        });
    });
});
