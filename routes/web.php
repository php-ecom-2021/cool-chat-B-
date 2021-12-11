<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


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
# generate table if not exists
if (!Schema::hasTable('chatrooms')) {
    Schema::create('chatrooms', function (Blueprint $table) {
		$table->string('chatroomID', 100);
		$table->dateTime('expireDate');
    });
}

// Route::get('home', function () {
//     // fører til standart startsiden
//     return view('home');
// });

// Nyoprettet forside
Route::get('front', function () {

    DB::delete("DELETE FROM chatrooms WHERE expireDate < NOW()", []);
    $chatRooms = DB::select("Select * from chatrooms");

    return view('home', [
        'chatRooms' => $chatRooms
        ]);
});

Route::get('chat', function () {
    // fetch messages if we want history
    $messages = [];

    DB::delete("DELETE FROM chatrooms WHERE expireDate < NOW()", []);
    $chatRooms = DB::select("Select * from chatrooms");

    return view('chat', [
        'messages' => json_encode($messages),
        'chatRooms' => $chatRooms
        ]);
});

Route::get('channels', function () {
    DB::delete("DELETE FROM chatrooms WHERE expireDate < NOW()", []);
    $chatRooms = DB::select("Select * from chatrooms");

    return view('channels', ['chatRooms' => $chatRooms]);
});

Route::get('/expire', function () {
    DB::delete("DELETE FROM chatrooms", []);
    return redirect('/');
});

Route::get('chat/{id}', function ($id) {
    // fetch messages if we want history
    $messages = [];
    // limit $id length to 100 chars or less
    $id = substr($id, 0, 100);
    // also lets be nice and replace spaces with underscores
    $id = str_replace(' ', '_', $id);
    // get all chatrooms
    $databaseIds = DB::select("Select * from chatrooms");
    // if we have passed expireDate delete those chatrooms
    // this is actually much faster and easier to execute exclusively in sql
    DB::delete("DELETE FROM chatrooms WHERE expireDate < NOW()", []);
    // loop all chatrooms and 
    for ($i = 0; $i < count($databaseIds); $i++) {
        //return only the id
        $databaseIds[$i] = $databaseIds[$i]->chatroomID;
    }
    // expires in 5 minutes
    $expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));
    // if chatroom exists after expired rooms are cleared
    if (in_array($id, $databaseIds)) {
        // update the expireDate 
        DB::update("UPDATE chatrooms 
        SET chatroomID=?, expireDate=? 
        WHERE chatroomID=?", [$id, $expires, $id]);
    } else {
        // if not, create a new entry
        DB::insert("INSERT INTO chatrooms 
        (chatroomID, expireDate)
        VALUES (?, ?)", [$id, $expires]);
    }
    // for debuggin'
    $chatRooms = DB::select("Select * from chatrooms");
    return view(
        'chat',
        [
            'messages' => json_encode($messages),
            'id' => $id,
            'chatRooms' => $chatRooms,
        ]
    );
});

Route::post('broadcast', function (Request $request) {
    event(new \App\Events\Message($request->channelID, $request->content, $request->user));
    return $request;
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
