<?php

use App\Http\Controllers\ArchivedBranchController;
use App\Http\Controllers\ArchivedBrandsController;
use App\Http\Controllers\ArchivedCarsController;
use App\Http\Controllers\ArchivedDriverController;
use App\Http\Controllers\ArchivedRentalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArchivedUsersController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RentalController;
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


Route::middleware('api')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('login', 'login');    
            Route::get('logs', 'logs');
            Route::post('store', 'store');        
            Route::post('update', 'update');
            Route::post('logout', 'logout');
            Route::post('update-password', 'updatePassword');
            Route::post('me', 'me');
        });           
    
        Route::post('upload-valid_id', 'validID');
        Route::post('upload-profile', 'profileImg');
        Route::post('update-profile', 'updateProfileImg');
        Route::post('remove-profile', 'deleteProfileImg');
    });
    
    Route::apiResource('archived-cars', ArchivedCarsController::class);
    Route::put('cars/approve/{id}', [CarController::class, 'approveCar']);
    Route::apiResource('cars', CarController::class);
    
    Route::apiResource('archived-brands', ArchivedBrandsController::class);
    Route::apiResource('brands', BrandController::class);
    
    Route::apiResource('archived-users', ArchivedUsersController::class);
    Route::apiResource('users', UserController::class);
    
    Route::apiResource('archived-drivers', ArchivedDriverController::class);
    Route::apiResource('drivers', DriverController::class);
    
    Route::apiResource('archived-branch', ArchivedBranchController::class);
    Route::apiResource('branch', BranchController::class);

    Route::apiResource('archived-rentals', ArchivedRentalController::class);
    Route::put('rentals/finished/{id}', [RentalController::class, 'markFinished']);
    Route::apiResource('rentals', RentalController::class);

    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::post('payment', [PaymentController::class, 'store']);
    Route::get('home', [HomeController::class, 'index']);
});

Route::controller(InquiryController::class)->group(function () {
    Route::get('inquiry','index');
    Route::post('inquiry', 'store');
    Route::delete('inquiry/{id}', 'destroy');
});

Route::post('upload-car-image', [ImageController::class, 'storeCar']);
Route::post('upload-image', [ImageController::class, 'store']);
Route::post('upload-image-license', [ImageController::class, 'storeLicense']);

