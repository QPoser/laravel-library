<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
