<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Settings\GeneralSetting;
use App\Models\Locale;
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

Route::namespace('App\Http\Controllers')->group(function(){
    Route::get('/test', 'BaseController@test');
    Route::get('/report/project', 'ProjectController@export');
    Route::get('/report/project/{id}', 'ProjectActivityController@export');
});

Route::get('{path}', function () {
    $data = resolve(GeneralSetting::class)->toArray();
    $data['locales'] = Locale::where('is_active', true)->get();
    return view('app', compact('data'));
})->where('path', '.*');
