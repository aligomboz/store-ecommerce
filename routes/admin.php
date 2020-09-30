<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//note that the prefix is admin for all file route 
Route::group(
    ['prefix' => LaravelLocalization::setLocale(),'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]],
function(){
    Route::group(['namespace' => 'Dashboard' , 'middleware'=>'auth:admin' , 'prefix' => 'admin'], function () {
        Route::get('/','DashboardController@index')->name('admin.dashboard');//thr first visit 
        Route::get('logout' , 'LoginController@logout')->name('admin.logout');
        Route::group(['prefix' => 'setting'], function () {
            Route::get('shipping-methode/{type}' , 'SettingsController@editShippingMethods')->name('editShippingMethods');
            Route::put('shipping-methode/{id}' , 'SettingsController@updateShippingMethods')->name('updateShippingMethods');
        });
        Route::group(['prefix' => 'profile'], function () {
            Route::get('edit' , 'ProfileController@editProfile')->name('editProfile');
            Route::put('update' , 'ProfileController@updateProfile')->name('updateProfile');
        });
        Route::resource('categories', 'MaiCategoriesController');
        Route::resource('sup-categories', 'SupCategoriesController');
        Route::resource('brands', 'BrandsController'); 
        Route::resource('tags', 'TagsController');
        Route::resource('products', 'ProductsController');
    });

    Route::group(['namespace' => 'Dashboard','middleware'=>'guest:admin' ,'prefix' => 'admin' ], function () {
        Route::get('/login','LoginController@login')->name('admin.login');
        Route::post('/login','LoginController@postlogin')->name('admin.post.login');
    });
   
});
 
