<?php

Route::get('/', 'Library\BookController@index')->name('library.books.home');

Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(
  [
      'prefix' => 'admin',
      'as' => 'admin.',
      'namespace' => 'Admin',
      'middleware' => ['auth', 'can:admin'],
  ],
  function () {
        Route::resource('authors', 'AuthorController');
        Route::post('/authors/{author}/set_active', 'AuthorController@setActive')->name('authors.set-active');
        Route::post('/authors/{author}/set_inactive', 'AuthorController@setInactive')->name('authors.set-inactive');

        Route::resource('genres', 'GenreController');
        Route::post('/genres/{genre}/set_active', 'GenreController@setActive')->name('genres.set-active');
        Route::post('/genres/{genre}/set_inactive', 'GenreController@setInactive')->name('genres.set-inactive');

        Route::resource('books', 'BookController');
        Route::post('/books/{book}/set_active', 'BookController@setActive')->name('books.set-active');
        Route::post('/books/{book}/set_inactive', 'BookController@setInactive')->name('books.set-inactive');

        // Route::resource('bundles', 'BundleController');
        Route::resource('users', 'UserController');
  }
);

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
            Route::post('/become-writer', 'ProfileController@becomeWriter')->name('become_writer');
            Route::post('/become-not-writer', 'ProfileController@becomeNotWriter')->name('become_not_writer');
        });

        Route::group(['prefix' => 'bundles', 'as' => 'bundles.'], function () {
            Route::get('/', 'BundleController@index')->name('home');
            Route::get('/show/{bundle}', 'BundleController@show')->name('show');
            Route::get('/create', 'BundleController@create')->name('create');
            Route::post('/store', 'BundleController@store')->name('store');
            Route::post('/add/{bundle}/{book}', 'BundleController@addToBundle')->name('add-book');
            Route::post('/remove/{bundle}/{book}', 'BundleController@removeFromBundle')->name('remove-book');
            Route::post('/remove/{bundle}', 'BundleController@destroy')->name('remove');
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
        Route::post('/{book}/review', 'Book\ReviewController@store')->name('books.review.add');
    }
);

Route::group(
    [
        'prefix' => 'bundles',
        'as' => 'library.',
        'namespace' => 'Library',
    ],
    function () {
        Route::get('/', 'BundleController@index')->name('bundles.home');
        Route::get('/{bundle}', 'BundleController@show')->name('bundles.show');
    }
);

Route::get('/users/{user}', 'Library\UserController@show')->name('library.users.show');
Route::post('/users/subscribe/{writer}/{subscriber}', 'Library\UserController@subscribe')->name('library.users.subscribe');
Route::post('/users/unsubscribe/{writer}/{subscriber}', 'Library\UserController@unsubscribe')->name('library.users.unsubscribe');
