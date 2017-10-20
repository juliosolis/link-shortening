<?php

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

use App\Link;

Route::get('/r/{hash}',function($hash) {
    //First we check if the hash is from a URL from our database
    $link = Link::where('hash','=',$hash)
        ->first();

    //If found, we redirect to the URL
    if ($link) {
        $link->clicks = $link->clicks + 1;
        $link->save();
        return Redirect::to($link->url);
    }
    //If not found, we redirect to index page with error message
    else {
        return Redirect::to('/')
            ->with('message','Invalid Hash');
    }
})->where('hash', '[0-9a-zA-Z]{6}');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/link', 'LinkController');