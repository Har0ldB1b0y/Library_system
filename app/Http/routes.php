<?php
Route::get('/', ['as' => 'show_homepage', 'uses' => 'PagesController@index']);
Route::get('/home', 'PagesController@index');
Route::post('/', 'PagesController@search');


//Auth
Route::auth();

//Users
Route::post('/admin/users/filter', 'Admin\UsersController@filter');
Route::resource('admin/users', 'Admin\UsersController');

//Books
Route::post('/admin/books/filter', 'Admin\BooksController@filter');
Route::resource('admin/books', 'Admin\BooksController');

//Materials
Route::post('/admin/materials/filter', 'Admin\MaterialsController@filter');
Route::resource('admin/materials', 'Admin\MaterialsController');

//Transactions
Route::post('/admin/transaction/filter', 'Admin\TransactionsController@filter');
Route::post('/admin/transaction/search-by-barcode', 'Admin\TransactionsController@searchByBarcode');
Route::resource('admin/transaction', 'Admin\TransactionsController');
Route::post('/admin/reserve-books/{book_id}', 'Admin\TransactionsController@reserve');
Route::post('/admin/approve-reservation/{book_id}', 'Admin\TransactionsController@approveBookReservation');
Route::post('/admin/return-books/{book_id}', 'Admin\TransactionsController@returnBook');
Route::post('/admin/return-books-exact/{book_id}', 'Admin\TransactionsController@returnBookWithExactAmount');
Route::post('/admin/reject-reservation/{book_id}', 'Admin\TransactionsController@rejectReservation');

//Select2 Ajax Search
Route::get('authors/search', 'UtilitiesController@searchAuthor');
Route::get('subjects/search', 'UtilitiesController@searchSubject');
Route::get('books/search', 'UtilitiesController@searchBook');
Route::get('users/search', 'UtilitiesController@searchUser');

//Change Password
Route::get('change-password', 'Auth\ChangePasswordController@form');
Route::post('change-password', 'Auth\ChangePasswordController@change');




