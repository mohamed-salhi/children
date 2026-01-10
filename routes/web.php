<?php

use App\Events\NewMessage;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

});

Route::get('login', [\App\Http\Controllers\Web\Auth\AuthController::class, 'index'])->name('user.login');
Route::post('login', [\App\Http\Controllers\Web\Auth\AuthController::class, 'login'])->name('user.login');
Broadcast::routes(['middleware' => ['auth']]);
Route::get('/test', function () {
    $response = Http::get('https://antaji.metricshop.net/api/categories');

// التأكد من نجاح الطلب
    if ($response->successful()) {
        $categories = $response->json(); // نحول البيانات إلى array

        foreach ($categories['data']['items'] as $userData) {
            \App\Models\Category::updateOrCreate(
                ['uuid' => $userData['uuid']], // شرط التحديث أو الإنشاء
                [
                    'name' => $userData['name_translate'],
                    // أضف أي حقول أخرى هنا
                ]
            );
        }
    }
    return \App\Models\Category::all();
})->name('name');
