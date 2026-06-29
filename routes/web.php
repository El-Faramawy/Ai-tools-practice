<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::prefix('admin')->middleware('web')->namespace('App\Http\Controllers\Admin')->group(function () {

    Route::get('login', 'AuthController@loginView')->name('admin.login');
    Route::post('login', 'AuthController@login')->name('admin.login.post');
    Route::get('logout', 'AuthController@logout')->name('admin.logout');

    //******* after login *******
    Route::group(['middleware' => 'admin'], function () {

        Route::get('/', function () {
            return redirect()->route('admins.index');
        })->name('admin.home');

        ################################### Profile ##########################################
        Route::get('admin_profile', 'AdminController@profile')->name('admin_profile');
        Route::post('update-profile', 'AdminController@update_profile')->name('admin_profile.update');

        ################################### Admins ##########################################
        Route::resource('admins', 'AdminController');
        Route::post('multi_delete_admins', 'AdminController@multiDelete')->name('admins.multiDelete');

    });


});//end Prefix
