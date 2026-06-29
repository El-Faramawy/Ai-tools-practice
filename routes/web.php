<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::prefix('admin')->middleware('web')->namespace('App\Http\Controllers\Admin')->group(function () {

    //******* after login *******
    Route::group(['middleware' => 'admin'], function () {

        ################################### Profile ##########################################
        Route::get('admin_profile', 'AdminController@profile')->name('admin_profile');
        Route::post('update-profile', 'AdminController@update_profile')->name('admin_profile.update');

        ################################### Admins ##########################################
        Route::resource('admins', 'AdminController');
        Route::post('multi_delete_admins', 'AdminController@multiDelete')->name('admins.multiDelete');

    });


});//end Prefix
