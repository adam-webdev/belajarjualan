// RajaOngkir Routes
Route::prefix('rajaongkir')->group(function () {
    Route::get('/provinces', [App\Http\Controllers\Shop\CheckoutController::class, 'getProvinces']);
    Route::get('/cities', [App\Http\Controllers\Shop\CheckoutController::class, 'getCities']);
    Route::get('/districts', [App\Http\Controllers\Shop\CheckoutController::class, 'getDistricts']);
    Route::post('/shipping-cost', [App\Http\Controllers\Shop\CheckoutController::class, 'getShippingCost']);
});