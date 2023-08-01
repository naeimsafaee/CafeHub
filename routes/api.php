<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Cart\PayController;
use App\Http\Controllers\TelegramController;
use App\Models\GarsonCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:10,1'])->group(function(){

    Route::middleware(['auth:api'])->group(function(){

        Route::apiResource("/item", ItemController::class);
        Route::apiResource("/client", ClientController::class);
        Route::get("/charge/{phone}", [ClientController::class , "charge"]);

        Route::apiResource("/cart", CartController::class);
        Route::get("/last_cart", [CartController::class, 'last_cart']);
        Route::get("/order_again", [CartController::class, 'order_again']);

        Route::apiResource("/event", EventController::class);

        Route::apiResource('home', HomeController::class);
        Route::apiResource('transaction', TransactionController::class);

        Route::apiResource('category', CategoryController::class);

        Route::post("/set_name", [AuthController::class, 'set_name']);
        Route::post("/reserv_cafe", [AuthController::class, 'reserv_cafe']);

        Route::get("/waze", [SettingController::class, 'waze']);
        Route::get("/google_map", [SettingController::class, 'google_map']);
        Route::get("/prices", [SettingController::class, 'prices']);
        Route::get("/download_link", [SettingController::class, 'download_link']);


        //        Route::post("/pay_event", [EventController::class, 'pay']);


        Route::post("/change_type_of_pay", [CartController::class, 'change_type_of_pay']);
        Route::post("/pay", [CartController::class, 'pay']);
        Route::get("/verify", [CartController::class, 'verify'])->name('cart_verify');

    });

    Route::post("/login", [AuthController::class, 'register']);
    Route::post("/verify", [AuthController::class, 'verify']);

    Route::get('pager/{table_number}', function($table_number){

        GarsonCall::query()->create(['table_number' => $table_number]);

        send_telegram_messages("میز شماره " . $table_number . " گارسون را صدا زده است!");

        return _response(true);
    });

    Route::post('/cart/verify' , [PayController::class , 'verify'])->name('cart_verify');

});

Route::post('/telegram' , [TelegramController::class , "get"])->name('telegram');


