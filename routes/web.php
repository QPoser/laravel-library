<?php

Route::get('/', 'Library\BookController@index')->name('library.books.home');

Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(
    [
        'prefix' => 'cabinet',
        'as' => 'cabinet.',
        'namespace' => 'Cabinet',
        'middleware' => ['auth'],
    ],
    function () {
        Route::get('/', 'HomeController@index')->name('home');

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('/', 'ProfileController@index')->name('home');
            Route::get('/edit', 'ProfileController@edit')->name('edit');
            Route::put('/update', 'ProfileController@update')->name('update');
        });

        Route::group(['prefix' => 'books', 'as' => 'books.'], function () {
            Route::get('/', 'BookController@index')->name('home');
            Route::get('/edit/{book}', 'BookController@edit')->name('edit');
            Route::put('/edit/{book}', 'BookController@update')->name('update');
            Route::get('/create', 'BookController@create')->name('create');
            Route::post('/store', 'BookController@store')->name('store');
            Route::post('/remove/{book}', 'BookController@destroy')->name('remove');
            Route::get('/{book}', 'BookController@show')->name('show');
        });

    }
);

Route::group(
    [
        'prefix' => 'books',
        'as' => 'library.',
        'namespace' => 'Library',
    ],
    function () {
        Route::get('/', 'BookController@index')->name('books.home');
        Route::get('/{book}', 'BookController@show')->name('books.show');
    }
);
