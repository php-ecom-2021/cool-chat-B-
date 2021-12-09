<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
Route::get('welcome', function(){
    // fører til standart startsiden
    return view('welcome');
});

// Nyoprettet forside
Route::get('', function(){

    return view('front');
});

Route::get('chat', function(){
    // fetch messages if we want history
    $messages = [];
    return view('chat', ['messages' => json_encode($messages)]);
});

Route::get('chat/{id}', function ($id){
    // fetch messages if we want history
    $messages = [];
    return view('chat', 
    [
        'messages' => json_encode($messages),
        'id' => $id
    ]);
});

Route::post('broadcast', function(Request $request){
    event(new \App\Events\Message($request->channelID, $request->content, $request->user));
    return 1;
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
