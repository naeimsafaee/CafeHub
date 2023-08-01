<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cart\AddController;
use App\Http\Controllers\Cart\PayController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Pages\EventController;
use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\ReserveController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\TelegramController;
use App\Models\GarsonCall;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/reserve_cafe', [ReserveController::class, 'show_success_reserve'])->name('show_success_reserve');

Route::get('/contact_us', [HomeController::class, 'contact_us'])->name('contact_us');

//profile
Route::middleware(['is_logged_in'])->group(function() {

    Route::middleware(['throttle:10,1'])->group(function() {

        Route::post('/cart/address2', [CartController::class, 'address2'])->name('address2');
        Route::post('/profile', [ProfileController::class, 'submit'])->name('profile');
        Route::post('/profile/avatar', [ProfileController::class, 'avatar_submit'])->name('avatar_submit');

    });
        //event
    Route::get('/event/add/{id}', [EventController::class, 'add_to_cart'])->name('add_to_event');
    Route::get('/event/delete/{id}', [EventController::class, 'destroy'])->name('delete_event');

    //pay

    //cart
    Route::get('/cart/address', [CartController::class, 'address'])->name('address');
    Route::get('/cart/send', [CartController::class, 'send'])->name('send');
    Route::get('/cart/add/{id}', [AddController::class, 'add_to_cart'])->name('add_to_cart');
    Route::get('/cart/delete/{id}', [AddController::class, 'destroy'])->name('delete_cart');

    Route::get('/pay_reserve', [EventController::class, 'pay_reserve'])->name('pay_reserve');

    Route::get('/profile', ProfileController::class)->name('profile');
    Route::get('/profile/last_cart', [ProfileController::class, 'last_cart'])->name('last_cart');
    Route::get('/profile/order_again/{transaction_id}', [ProfileController::class, 'order_again'])->name('order_again');

    Route::get('/cart/pay', [PayController::class, 'pay'])->name('pay');
    Route::get('/cart/pay_type', [PayController::class, 'pay_type'])->name('pay_type');
    Route::get('/cart/success', [CartController::class, 'success'])->name('success');

});

Route::get('pager/{table_number}', function($table_number){

    GarsonCall::query()->create(['table_number' => $table_number]);

    send_telegram_messages("میز شماره " . $table_number . " گارسون را صدا زده است!");

    return redirect()->back();
})->name('call_waiter');

//login
Route::get('/login', LoginController::class)->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/verify', [LoginController::class, 'verify'])->name('verify');
Route::get('/name', [LoginController::class, 'name'])->name('name');


Route::group(['prefix' => 'admin'], function() {
    Voyager::routes();
});

Route::get('/telegram/set_webhook', [TelegramController::class, 'set_webhook']);

Route::middleware(['throttle:10,1'])->group(function() {

    Route::post('/login', [LoginController::class, 'register'])->name('login');
    Route::post('/verify', [LoginController::class, 'code'])->name('verify');
    Route::post('/name', [LoginController::class, 'name_submit'])->name('name');
    Route::post("/event_verify", [\App\Http\Controllers\Api\EventController::class, 'verify'])->name('event_verify');
    Route::post('/contact_us', [HomeController::class, 'contact_us_submit'])->name('contact_us_submit');
    Route::post('/reserve_cafe', [ReserveController::class, 'reserve_cafe'])->name('reserve_cafe');

});

