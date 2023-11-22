<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PostulationController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\RateController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::prefix('v1')->group(function () {
    //Prefijo V1, todo lo que este dentro de este grupo se accedera escribiendo v1 en el navegador, es decir /api/v1/*
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('createcategory', [CategoryController::class, 'store']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('createclient', [ClientController::class, 'store']);


    Route::group(['middleware' => ['jwt.verify']], function () {
        //Todo lo que este dentro de este grupo requiere verificación de usuario.
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('get-user', [AuthController::class, 'getUser']);
        Route::post('validatetoken', [AuthController::class, 'validateToken']);

        Route::prefix('client')->group(function () {
        });

        Route::prefix('worker')->group(function () {
            Route::post('createworker', [WorkerController::class, 'stor e']);
        });

        Route::prefix('service')->group(function () {
            Route::post('createservice', [ServiceController::class, 'store']);
            Route::get('getoffers', [ServiceController::class, 'getOffers']);
            Route::post('postulate/{service}', [PostulationController::class, 'postulate']);
            Route::get('aplicants/{service}', [PostulationController::class, 'getApplicants']);
            Route::post('acceptaplicants/{service}', [PostulationController::class, 'acceptApplicant']);
            Route::get('getservicesclient', [ServiceController::class, 'indexClient']);
            Route::get('getallservicesclient', [ServiceController::class, 'indexClientAll']);
            Route::get('getallservicesworker', [ServiceController::class, 'indexWorker']);
            Route::get('getservice/{service}', [ServiceController::class, 'show']);
            Route::get('getrate/{service}', [ServiceController::class, 'getRate']);
            Route::get('getuserservice/{service}', [ServiceController::class, 'getUserService']);
            Route::get('getvoucher/{service}', [ServiceController::class, 'getVoucher']);
            Route::post('toverifyvoucher/{voucher}', [ServiceController::class, 'toVerifyVoucher']);
            Route::post('validatevoucher/{voucher}', [ServiceController::class, 'validateVoucher']);
        });

        Route::prefix('rate')->group(function () {
            Route::get('{service}', [RateController::class, 'rate']);
            Route::post('{service}', [RateController::class, 'store']);
            Route::patch('worker/{service}', [RateController::class, 'rateWorker']);
            Route::patch('client/{service}', [RateController::class, 'rateClient']);
        });

    });
});
