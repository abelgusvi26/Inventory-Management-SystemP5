<?php

/*
|--------------------------------------------------------------------------
| Web Routes - Inventory Management System
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('login', 'AuthController@showLogin')->name('login');
Route::post('login', 'AuthController@login')->name('login.post');
Route::get('logout', 'AuthController@logout')->name('logout');
Route::post('logout', 'AuthController@logout')->name('logout.post');

// Authenticated Routes
Route::group(['middleware' => 'auth'], function () {
    // Dashboard
    Route::get('/', 'DashboardController@index')->name('home');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    // Products
    Route::get('/products', 'ProductController@index')->name('products.index');
    Route::get('/products/create', 'ProductController@create')->name('products.create');
    Route::post('/products', 'ProductController@store')->name('products.store');
    Route::get('/products/{id}', 'ProductController@detail')->name('products.detail');
    Route::get('/products/{id}/edit', 'ProductController@edit')->name('products.edit');
    Route::put('/products/{id}', 'ProductController@update')->name('products.update');
    Route::delete('/products/{id}', 'ProductController@destroy')->name('products.destroy');

    // Categories
    Route::get('/categories', 'CategoryController@index')->name('categories.index');
    Route::get('/categories/create', 'CategoryController@create')->name('categories.create');
    Route::post('/categories', 'CategoryController@store')->name('categories.store');
    Route::get('/categories/{id}/edit', 'CategoryController@edit')->name('categories.edit');
    Route::put('/categories/{id}', 'CategoryController@update')->name('categories.update');
    Route::delete('/categories/{id}', 'CategoryController@destroy')->name('categories.destroy');

    // Suppliers
    Route::get('/suppliers', 'SupplierController@index')->name('suppliers.index');
    Route::get('/suppliers/create', 'SupplierController@create')->name('suppliers.create');
    Route::post('/suppliers', 'SupplierController@store')->name('suppliers.store');
    Route::get('/suppliers/{id}', 'SupplierController@detail')->name('suppliers.detail');
    Route::get('/suppliers/{id}/edit', 'SupplierController@edit')->name('suppliers.edit');
    Route::put('/suppliers/{id}', 'SupplierController@update')->name('suppliers.update');
    Route::delete('/suppliers/{id}', 'SupplierController@destroy')->name('suppliers.destroy');

    // Transactions (Stock In / Stock Out / History)
    Route::get('/transactions/history', 'TransactionController@history')->name('transactions.history');
    Route::get('/transactions/in', 'TransactionController@in')->name('transactions.in');
    Route::post('/transactions/in', 'TransactionController@storeIn')->name('transactions.store_in');
    Route::get('/transactions/out', 'TransactionController@out')->name('transactions.out');
    Route::post('/transactions/out', 'TransactionController@storeOut')->name('transactions.store_out');

    // Reports
    Route::get('/reports', 'ReportController@index')->name('reports.index');
    Route::get('/reports/print', 'ReportController@printView')->name('reports.print');
    Route::get('/reports/export', 'ReportController@exportCsv')->name('reports.export');

    // User Administration (Admin Only Middleware)
    Route::group(['middleware' => 'admin'], function () {
        Route::get('/users', 'UserController@index')->name('users.index');
        Route::get('/users/create', 'UserController@create')->name('users.create');
        Route::post('/users', 'UserController@store')->name('users.store');
        Route::get('/users/{id}/edit', 'UserController@edit')->name('users.edit');
        Route::put('/users/{id}', 'UserController@update')->name('users.update');
        Route::delete('/users/{id}', 'UserController@destroy')->name('users.destroy');
    });
});
