<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::match(['get', 'post'], '/gmotwnotify', 'PaymentController@paymentNotify');

Route::post('cancelOrder', 'SellManage@cancelOrder');
Route::prefix('seatSale')->group(function () {
    Route::patch('/{seat_sale_id}', 'SellManage@changeVisitStatus')->middleware('check_seat_sale_id'); //STS 2021/09/10 Task 48.2
});
