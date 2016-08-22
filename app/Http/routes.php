<?php
Route::get('/', ['as' => 'show_homepage', 'uses' => 'PagesController@index']);
Route::get('/home', 'PagesController@index');

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



