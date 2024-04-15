<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrawController;
use App\Http\Controllers\API\Member\CartController;
use App\Http\Controllers\API\Admin\SendNotificationController;
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


Route::get('/', function () {
    return view('welcome');
});


Route::get('/send', function () {
  
    return view('testPusher');
})->name('send');


Route::get('notification', [SendNotificationController::class,'create'])->name('notification.create');
Route::post('notification', [SendNotificationController::class,'store'])->name('notification.store');
Route::get('/crawl-data/{key}', [CrawController::class, 'getPathForm'])->name('craw-data');
Route::post('/crawl-data', [CrawController::class, 'getCrawlData']);


