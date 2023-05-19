<?php

use Illuminate\Http\Request;
use Modules\Flight\Controllers\FlightController;


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

Route::get('/api/search-grid', 'FlightController@searchFlightsGrid')->name('searchGrid');////////////////////////////////////////////////////////////////
// Route::get('/search-grid', function(){
//     dd('ok');
// })->name('searchGrid');
Route::get('/search-grid-api', 'Api\ApiController@searchFlightsGrid')->name('searchGrid.api');////////////////////////////////////////////////////////////////
Route::get('/bus', 'BusController@busbookingSearch')->name('busbookingSearch');

Route::get('/airports', 'Api\ApiController@airports');////////////////////////////////////////////////////////////////

Route::post('/myPassengerDetails', 'Api\ApiController@myPassengerDetails');
Route::get('/pnr', 'Sabre\SoapController@getPNR');
Route::get('/airlines', 'HomeController@airLines')->name('airLines');

Route::get('/cancelpnr', 'Sabre\SoapController@cancelPNR');
Route::get('/issuetkt', 'Sabre\SoapController@issueTicket');
Route::get('/cancelseat', 'Sabre\SoapController@cancelSeat');
Route::post('/bookingfromweb', 'Sabre\SoapController@bookingFromWeb');

Route::post('/search-airport', 'Api\ApiController@ajaxSearchSingleAirport');////////////////////////////////////////////////////////////////
Route::get('/search-airport', 'Api\ApiController@ajaxSearchSingleAirportGet');////////////////////////////////////////////////////////////////


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/getVerificationCode', 'HomeController@getCode');
Route::get('/resendVerificationCode', 'HomeController@resendCode');
// Route::get('/getVerificationCodeGuest', 'HomeController@getCodeGuest');
Route::post('/verifyVerificationCode', 'HomeController@verifyCode');
Route::post('/verifyVerificationCodeGuest', 'HomeController@verifyCodeGuest')->name("test");
Route::post('/login', 'HomeController@login');
Route::get('/reservationtable', 'HomeController@getResTable');

Route::post('/req-tour-web', 'Api\ApiController@postTourRequestWeb')->name('postTourRequest');
// Route::post('/req-tour-web', 'Api\ApiController@postTourRequestWebOld')->name('postTourRequestOld');

Route::post('/req-custom-tour', 'Api\ApiController@postCustomTourRequestWeb')->name('postCustomTourRequestWeb');

//--------mobile apis New

Route::get('/v2/search', 'Api\ApiController@searchFlights'); //->middleware('verifyToken2');
Route::get('/v2/tours', 'TourController@getAllTours')->middleware('verifyToken2');
Route::get('/v2/airportsV2', 'Api\ApiController@airportsV2')->middleware('verifyToken2'); ////////////////////////////////////////////////////////////////
Route::get('/v2/airlines', 'HomeController@airLines')->name('airLinesv2')->middleware('verifyToken2');

Route::post('/v2/req-tour', 'Api\ApiController@postTourRequest')->middleware('verifyToken2');
Route::post('/v2/booking', 'Api\ApiController@booking')->middleware('verifyToken2');
Route::post('/v2/airblue-booking', 'Api\ApiController@airblueBooking')->middleware('verifyToken2');

Route::post('/v2/bus/order', 'BusController@busCheckoutPostNewForMobile')->middleware('verifyToken2');
Route::get('v2/airbooking/cancel', 'HomeController@airbookingCancel')->middleware('verifyToken2');
// for bus and cinema order creation
Route::post('/order/create', 'Api\ApiController@createOrder')->middleware('verifyToken2');
Route::post('v2/order/create', 'Api\ApiController@createOrder')->middleware('verifyToken2');

Route::post('v2/order/create-new', 'Api\ApiController@createOrdernew')->middleware('verifyToken2');

Route::post('/order/selectPaymentMethod', 'Api\ApiController@selectPaymentMethod')->middleware('verifyToken2');

Route::post('v2/order/selectPaymentMethod', 'Api\ApiController@selectPaymentMethod')->middleware('verifyToken2');

Route::get('/v2/booking-list', 'Api\ApiController@bookingList')->middleware('verifyToken2');
Route::get('/order/getOrderDetail', 'Api\ApiController@getOrderDetail')->middleware('verifyToken2');
Route::get('/order-new', 'Api\ApiController@getOrderDetailNew');
Route::post('/update-order', 'Api\ApiController@updateNewOrder');
Route::post('/update-order-corp', 'Api\ApiController@updateNewOrderCorporate');


Route::get('/v2/orders', 'Api\ApiController@getAllOrders')->middleware('verifyToken2');
Route::get('v2/order', 'Api\ApiController@getNewOrderDetail')->middleware('verifyToken2');
Route::get('v2/order/checkout', 'Api\ApiController@getPaymentGatewayURL')->middleware('verifyToken2');
//--------mobile apis old

Route::get('/search', 'Api\ApiController@searchFlights');
Route::get('/tours', 'TourController@getAllTours');
Route::get('/airportsV2', 'Api\ApiController@airportsV2');

Route::post('/req-tour', 'Api\ApiController@postTourRequestOld')->middleware('verifyToken');
Route::post('/booking', 'Api\ApiController@sabreBookingOld')->middleware('verifyToken');
Route::post('/airblue-booking', 'Api\ApiController@airblueBookingOld')->middleware('verifyToken');

Route::post('/airline-price-type', 'Api\ApiController@addAirlinePriceType');

//---------------------Corporate
Route::get('/search-corporate', 'Api\ApiController@searchFlightsCorporate');
Route::post('/checkout-corporate', 'Api\ApiController@checkoutCorporate');
Route::post('/checkout-corporate-air-blue', 'Api\ApiController@AirblueCheckoutApi');



Route::post('/login-qr', 'Api\ApiController@loginQr')->middleware('verifyToken2');

Route::patch('airbooking-cancel-system', 'HomeController@airbookingCancelForSystem');

Route::post('/upload-receipt', 'Api\ApiController@uploadReceipt')->name('uploadreceipt')->middleware('verifyToken2');

Route::middleware('checkSystemToken')->get('/sys', function (Request $request) {
    $resp = [
        'success' => true,
        'message' => 'You are good to go',
    ];
    return response($resp, 200);
});
Route::group(['prefix' => 'sip'], function () {
    Route::get('/config', 'Sip\SipController@config')->name('sip.config');
});
Route::get('v2/sip/config', 'Sip\SipController@config')->name('sip.config')->middleware('verifyToken2');
Route::post('/order/lock', 'Api\ApiController@lockUnlockOrders');

Route::post('v2/order-confirm-cancel', 'Api\ApiController@orderConfirmCancel')->name('order.confirm.cancell')->middleware('verifyToken2');

Route::post('v2/order-confirm-cancel-new', 'Api\ApiController@orderConfirmCancelNew')->name('order.confirm.cancell.new')->middleware('verifyToken2');
