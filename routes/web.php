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

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\InsuranceController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Modules\User\Controllers\WalletController;

// Route::get('testmypay/{amount}/{id}', '\App\Http\Controllers\HomeController@myPayRequest');
// Route::get('testticket', '\App\Http\Controllers\HomeController@testTicket');
// Route::get('advancecalender', '\App\Http\Controllers\HomeController@advanceCalenderSearch');
Route::get('/intro', '\App\Http\Controllers\LandingpageController@index');
Route::get('/', '\App\Http\Controllers\HomeController@index');
Route::get('/home', '\App\Http\Controllers\HomeController@index')->name('home');
Route::post('/install/check-db', '\App\Http\Controllers\HomeController@checkConnectDatabase');

Route::get('/update', function () {
    return redirect('/');
});

//Cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cleared!";
})->middleware(['auth', 'dashboard']);

//Login
Auth::routes(['verify' => true]);

// Search Flight

Route::post('/searchflights', 'FlightController@searchFlights')->name('searchflights');////////////////////////////////////////////////////////////////
Route::get('/airportsdata', 'FlightController@airportsData')->name('airportsdata');////////////////////////////////////////////////////////////////
Route::get('/airlinesdata', 'FlightController@airlinesData')->name('airlinesdata');////////////////////////////////////////////////////////////////
Route::get('/airportcitycountry', 'FlightController@airportCityCountry')->name('airportcitycountry');////////////////////////////////////////////////////////////////


//Custom User Login and Register
Route::post('register', '\Modules\User\Controllers\UserController@userRegister')->name('auth.register');
Route::post('login', '\Modules\User\Controllers\UserController@userLogin')->name('auth.login');
Route::post('logout', '\Modules\User\Controllers\UserController@logout')->name('auth.logout');
// Social Login
Route::get('social-login/{provider}', '\App\Http\Controllers\Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', '\App\Http\Controllers\Auth\LoginController@socialCallBack');

// Logs
Route::get('admin/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(['auth', 'dashboard', 'system_log_view']);
Route::group(['prefix' => '/airblue'], function () {
    Route::post('/booking', 'Airblue\AirBlueController@bookingFromWebAirBlue')->name('confirmbookingAirBlue');
    Route::get('/checkout', 'Airblue\AirBlueController@AirblueCheckout')->name('AirblueCheckout');
    Route::get('/success', 'Airblue\AirBlueController@airblueSuccess')->name('airbluesuccess');
});

Route::get('/package-payment', 'homecontroller@myPayDeduction');
Route::get('/ajax-search-airports', 'homecontroller@ajaxsearchairports')->name('ajax.search.airports');////////////////////////////////////////////////////////////////
Route::get('/ajax-search-airport', 'HomeController@ajaxSearchSingleAirport')->name('ajax-search-airport');////////////////////////////////////////////////////////////////
Route::get('/checkoutFinal', 'HomeController@checkoutGet')->name('checkoutFinal');
Route::get('/checkoutSummary/{itinerary}', 'HomeController@checkoutSummary')->name('checkoutSummary');
Route::get('/checkout/{itinerary}', 'HomeController@checkoutGetNew')->name('checkoutGetNew');
Route::post('/post/checkout', 'HomeController@checkout')->name('postCheckout');
Route::get('/booking', 'Sabre\SoapController@booking');
Route::post('/booking', 'Sabre\SoapController@bookingFromWeb')->name('confirmbooking'); // this route is for sabre booking
// Route::get('/getVerificationCodeGuest', 'HomeController@getCodeGuest')->name('guest.otp-request');
Route::get('/update-profile', 'homecontroller@profileupdate')->name('profile.update');
Route::get('/user-checkout/{itinerary}', 'HomeController@checkoutGet');

Route::get('/airpending', 'HomeController@AirPending')->name('airpending');
Route::get('/paymentmethod', 'HomeController@getpaymentmethod')->name('getPaymentMethod');
Route::post('/paymentmethod', 'HomeController@postpaymentmethodNewwNew')->name('postPaymentMethod');
Route::get('issue-ticket-request/{id}/{type}', [HomeController::class, 'issueTicketRequest'])->name("issue-ticket-request");
Route::get('issue-ticket/{id}', [WalletController::class, 'issueTicket'])->name("agent-issue-ticket-request");
Route::post('/paymentsuccess', 'HomeController@paymentsuccess')->name('paymentsuccess');
Route::get('/jazzcashcallback', 'HomeController@paymentCallBack')->name('jazzcashcallback');

Route::get('/selectUser/{id}', 'HomeController@selectUser')->name('selectUser');


Route::get('/get-location', 'HomeController@getLocation')->name('get.location');////////////////////////////////////////////////////////////////

// local booking

Route::post('/localbooking', 'HomeController@localBooking')->name('localbooking');

Route::post('/receipt', 'HomeController@receipt')->name('receipt');

Route::get('/getOTP', 'HomeController@getCodeGuest')->name('getotp');
Route::post('/verifyOTP', 'HomeController@verifyCodeGuest')->name('verifyotp');


/* Insurance routes start */

Route::get('insurance', 'InsuranceController@index')->name('home.insurance');
Route::get('insurance/search', 'InsuranceController@search')->name('search.insurance');
Route::post('insurance/booking', 'InsuranceController@bookingInsurance')->name('booking.insurance');
Route::post('insurance/checkout', 'InsuranceController@checkoutInsurance')->name('checkout.insurance');
Route::post('insurance/checkout/comfirm', 'InsuranceController@checkoutInsuranceComfirm')->name('checkout.insurance.comfirm');
/* Insurance routes end */


//Dummy Route
Route::get('/checkout-ticket', 'HomeController@checkoutTicket');
