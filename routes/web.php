<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// Add this to your web.php file

// This line makes all routes in it to use the auth middleware, meaning only signed in users can access these routes
Route::middleware('auth')->group(function () {

    // This route creates classes for teachers
    Route::post("/create_class", 'SessionsController@createClass')
        ->name('create_class');

    // This route is used by both teachers and students to join a class
    Route::get("/classroom/{id}", 'SessionsController@showClassRoom')
        ->where('id', '[0-9]+')
        ->name('classroom');
});
