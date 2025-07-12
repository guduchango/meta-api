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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/test/test',function(Request $request){
  echo "Hello from the test route!";
});

Route::get('/webhook/',
    [\App\Http\Controllers\ApiController::class, 'verifyWebhook'])
    ->name('verifyWebhook');

Route::post('/webhook/',
    [\App\Http\Controllers\ApiController::class, 'webhook'])
    ->name('webhook');

Route::get('/show_webhook/',
    [\App\Http\Controllers\ApiController::class, 'showWebhook'])
    ->name('show_webhook');


Route::get('/get_waiting_chats/',
    [\App\Http\Controllers\ApiController::class, 'getWaitingChats'])
    ->name('getWaitingChats');

Route::post('/send_response/',
    [\App\Http\Controllers\ApiController::class, 'sendResponse'])
    ->name('sendResponse');
