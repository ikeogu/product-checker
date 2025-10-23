<?php


use App\Http\Controllers\ProductVerificationController;
use App\Modules\Company\Controllers\CompanyController;
use App\Modules\Onboarding\Controllers\AuthController;
use App\Modules\Product\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/login', function () {
    return response()->json(['message' => 'Login not available'], 404);
})->name('login');

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('/register', 'register');
        Route::post('/register-company', 'registerCompanyUser');
        Route::post('/login', 'login');

        Route::get('/google', 'googleRedirect');
        Route::get('/google/callback', 'googleCallback');
    });

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('products')->controller(ProductController::class)
        ->group(function () {
            Route::get('/{id}',  'show');
            Route::post('/',  'store');
            Route::put('/{id}',  'update');
            Route::delete('/{id}',  'destroy');
        });

    Route::middleware('role:company')
        ->prefix('companies')
        ->controller(CompanyController::class)
        ->group(function () {
            Route::post('{company}/children', 'addChildren');
            Route::get('{company}/children', 'listChildren');
        });
});

Route::get('/products', [ProductController::class, 'index']);
Route::post('/products/verify', [ProductVerificationController::class, 'verify'])->name('products.verify');
