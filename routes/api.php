<?php


use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\Example\Sample2Controller;
use App\Http\Controllers\Api\V1\Example\SampleController;
use App\Http\Controllers\Api\V1\Profile\ChangeAvatarController;
use App\Http\Controllers\Api\V1\Profile\ChangePasswordController;
use App\Http\Controllers\Api\V1\Profile\ChangeProfileController;
use App\Http\Controllers\Api\V1\Profile\ProfileController;
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




Route::prefix('v1')->middleware(['guard_api'])->group(function () {

    Route::prefix('auth')->middleware('throttle:15,1')->group(function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
        Route::post('verify-password', [ResetPasswordController::class, 'verifyResetPassword']);
        Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']);
        Route::post('verify-email', [AuthController::class, 'verifyEmail']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('auth')->middleware('throttle:15,1')->group(function () {
            Route::post('logout', [LoginController::class, 'logout']);
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [ProfileController::class, 'index']);
            Route::delete('/', [ProfileController::class, 'deleteUser']);
            Route::post('change-profile', [ChangeProfileController::class, 'store']);
            Route::post('change-password', [ChangePasswordController::class, 'store']);
            Route::post('change-avatar', [ChangeAvatarController::class, 'store']);
        });

        Route::prefix('example')->group(function () {
            Route::apiResource('samples', SampleController::class);
            Route::apiResource('samples2', Sample2Controller::class);
        });
    });
});
