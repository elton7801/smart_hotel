<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\HomeController;
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

//Admin Controller

Route::get('/',[AdminController::class,'home']);

Route::get('/home',[AdminController::class,'index'])->name('home');

Route::get('/create_room',[AdminController::class,'create_room'])
->middleware(['auth','admin']);

Route::post('/add_room',[AdminController::class,'add_room'])
->middleware(['auth','admin']);

Route::get('/view_room',[AdminController::class,'view_room'])
->middleware(['auth','admin']);

Route::get('/delete_room/{id}',[AdminController::class,'delete_room'])
->middleware(['auth','admin']);

Route::get('/edit_room/{id}',[AdminController::class,'edit_room'])
->middleware(['auth','admin']);

Route::post('/update_room/{id}',[AdminController::class,'update_room'])
->middleware(['auth','admin']);

Route::get('/bookings',[AdminController::class,'bookings'])
->middleware(['auth','admin']);

Route::get('/delete_booking/{id}',[AdminController::class,'delete_booking'])
->middleware(['auth','admin']);

Route::get('/approve_book/{id}',[AdminController::class,'approve_book'])
->middleware(['auth','admin']);

Route::get('/reject_book/{id}',[AdminController::class,'reject_book'])
->middleware(['auth','admin']);

Route::get('/view_gallary',[AdminController::class,'view_gallary'])
->middleware(['auth','admin']);

Route::post('/upload_gallary',[AdminController::class,'upload_gallary'])
->middleware(['auth','admin']);

Route::get('/delete_gallary/{id}',[AdminController::class,'delete_gallary'])
->middleware(['auth','admin']);

Route::get('/message',[AdminController::class,'message'])
->middleware(['auth','admin']);

Route::get('/send_mail/{id}',[AdminController::class,'send_mail'])
->middleware(['auth','admin']);

Route::post('/mail/{id}',[AdminController::class,'mail'])
->middleware(['auth','admin']);




//Home Controller

Route::get('/our_room',[HomeController::class,'our_room']);

Route::get('/hotel_gallary',[HomeController::class,'hotel_gallary']);

Route::get('/contact_us',[HomeController::class,'contact_us']);

Route::get('/room_details/{id}',[HomeController::class,'room_details']);

Route::post('/add_booking/{id}',[HomeController::class,'add_booking']);

Route::post('/contact',[HomeController::class,'contact']);

Route::get('/search_availability',[HomeController::class,'search_availability']);

Route::get('/my_booking',[HomeController::class,'my_booking']);

Route::get('/view_booking/{id}',[HomeController::class,'view_booking']);

Route::get('/cancel_booking/{id}',[HomeController::class,'cancel_booking']);



