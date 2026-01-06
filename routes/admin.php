<?php

use App\Http\Controllers\Admin\PaymentGateway\ProcessPaymentController;
use App\Http\Controllers\Admin\Places\CityController;
use App\Http\Controllers\Admin\Places\CountryController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        Route::get('admin/login', function () {
            return view('admin.auth.login');
        });
        Route::get('admin/login', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'index'])->name('admin.login');
        Route::post('admin/login', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'login'])->name('login');

        Route::middleware('auth:admin')->prefix('admin')->group(function () {
            Route::post('logout', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'logout'])->name('logout');

            Route::controller(\App\Http\Controllers\Admin\User\UserController::class)->prefix('users')->name('users.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{id}', 'updateStatus')->name('updateStatus');
            });


            Route::middleware('permission:admin')->controller(\App\Http\Controllers\Admin\Role\RolesController::class)->name('roles.')->prefix('roles')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{uuid}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{uuid}', 'updateStatus')->name('updateStatus');

            });

            Route::middleware('permission:admin')->controller(\App\Http\Controllers\Admin\AdminController::class)->name('managers.')->prefix('managers')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{id}', 'updateStatus')->name('updateStatus');
                Route::get('/edit/{id}', 'edit')->name('edit');
            });
            Route::middleware('permission:product')->controller(\App\Http\Controllers\Admin\Product\ProductController::class)->prefix('products')->name('products.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{uuid}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{uuid}', 'updateStatus')->name('updateStatus');
            });

            Route::middleware('permission:city')->controller(\App\Http\Controllers\Admin\Delivery\DeliveryController::class)->prefix('deliveries')->name('deliveries.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{uuid}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{uuid}', 'updateStatus')->name('updateStatus');
            });
            Route::middleware('permission:category')->controller(\App\Http\Controllers\Admin\Category\CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{uuid}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{uuid}', 'updateStatus')->name('updateStatus');
            });
            Route::middleware('permission:contact-us')->controller(\App\Http\Controllers\Admin\Contact\ContactController::class)->prefix('contacts')->name('contacts.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::delete('/{uuid}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::post('/view/{uuid}', 'view')->name('view');
                Route::post('/importance/{uuid}/{importance}', 'importance')->name('importance');
                Route::get('/texts', 'textsContent')->name('texts');
                Route::post('/texts', 'postTextsContent')->name('texts.post');
            });
            Route::middleware('permission:setting')->prefix('content/')->name('content.')->group(function () {
                Route::get('hero', [\App\Http\Controllers\Admin\Content\ContentController::class, 'getHeroSection'])->name('getHeroSection');
                Route::post('hero', [\App\Http\Controllers\Admin\Content\ContentController::class, 'postHeroSection'])->name('postHeroSection');

                Route::get('journey', [\App\Http\Controllers\Admin\Content\ContentController::class, 'getJourneySection'])->name('getJourneySection');
                Route::post('journey', [\App\Http\Controllers\Admin\Content\ContentController::class, 'postJourneySection'])->name('postJourneySection');

                Route::get('service', [\App\Http\Controllers\Admin\Content\ServiceController::class, 'index'])->name('service.index');
                Route::post('service/update', [\App\Http\Controllers\Admin\Content\ServiceController::class, 'update'])->name('service.update');
                Route::get('service/indexTable', [\App\Http\Controllers\Admin\Content\ServiceController::class, 'indexTable'])->name('service.indexTable');

                Route::get('features', [\App\Http\Controllers\Admin\Content\FeatureController::class, 'index'])->name('features.index');
                Route::post('features/update', [\App\Http\Controllers\Admin\Content\FeatureController::class, 'update'])->name('features.update');
                Route::get('features/indexTable', [\App\Http\Controllers\Admin\Content\FeatureController::class, 'indexTable'])->name('features.indexTable');

            });
        });


    });
