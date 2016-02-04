<?php


Route::get('/', 'PagesController@index');
Route::get('/home', 'PagesController@index');

Route::get('auth/login', 'Auth\AuthController@redirectToTeleserv');
Route::get('auth/logout', 'Auth\AuthController@globalLogout');
Route::get('auth/teleserv/callback', 'Auth\AuthController@handleTeleservCallback');
