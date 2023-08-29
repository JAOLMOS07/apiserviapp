<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
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

    Route::group(['middleware' => ['jwt.verify']], function () {
        //Todo lo que este dentro de este grupo requiere verificación de usuario.
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('get-user', [AuthController::class, 'getUser']);

        Route::prefix('client')->group(function () {
            Route::post('createclient', [ClientController::class, 'store']);

        });
        Route::prefix('worker')->group(function () {
            Route::post('createworker', [WorkerController::class, 'store']);


        });
        Route::prefix('service')->group(function () {
            Route::post('createservice', [ServiceController::class, 'store']);
            Route::get('getoffers', [ServiceController::class, 'getOffers']);
            Route::post('postulate/{service}', [ServiceController::class, 'postulate']);
            Route::get('aplicants/{service}', [ServiceController::class, 'aplicants']);
            Route::post('acceptaplicants/{service}', [ServiceController::class, 'acceptAplicant']);

            Route::prefix('contract')->group(function () {
                Route::get('{service}', [ContractController::class, 'getContract']);
                Route::patch('{contract}', [ContractController::class, 'AcceptContract']);
                Route::post('createcontract/{service}', [ContractController::class, 'store']);


            });
            Route::prefix('rate')->group(function () {
                Route::get('{service}', [RateController::class, 'rate']);
                Route::post('{service}', [RateController::class, 'store']);
                Route::patch('worker/{service}', [RateController::class, 'rateWorker']);
                Route::patch('client/{service}', [RateController::class, 'rateClient']);


            });
        });


    });
});
