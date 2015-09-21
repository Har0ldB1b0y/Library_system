<?php


get('/', 'PagesController@index');
get('/home', 'PagesController@index');

get('auth/login', 'Auth\AuthController@redirectToTeleserv');
get('auth/logout', 'Auth\AuthController@globalLogout');
get('auth/teleserv/callback', 'Auth\AuthController@handleTeleservCallback');
