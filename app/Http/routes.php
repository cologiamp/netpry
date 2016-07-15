<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', [
	'uses' => '\chatty\Http\Controllers\HomeController@index',
	'as' => 'home',
]);

Route::get('/signup', [
	'uses' => '\chatty\Http\Controllers\AuthController@getSignup',
	'as' => 'auth.signup',
	'middleware' => ['guest'],
]);

Route::post('/signup', [
	'uses' => '\chatty\Http\Controllers\AuthController@postSignup',
	'middleware' => ['guest'],
]);

Route::get('/signin', [
	'uses' => '\chatty\Http\Controllers\AuthController@getSignin',
	'as' => 'auth.signin',
	'middleware' => ['guest'],
]);

Route::post('/signin', [
	'uses' => '\chatty\Http\Controllers\AuthController@postSignin',
	'middleware' => ['guest'],
]);

Route::get('/signout', [
	'uses' => '\chatty\Http\Controllers\AuthController@getSignout',
	'as' => 'auth.signout',
]);

/***SEARCH **/

Route::get('/search', [
	'uses' => '\chatty\Http\Controllers\SearchController@getResults',
	'as' => 'search.results',
]);

/***USER PROFILE ****/

Route::get('/user/{username}', [
	'uses' => '\chatty\Http\Controllers\ProfileController@getProfile',
	'as' => 'profile.index',
]);

Route::get('/profile/edit', [
	'uses' => '\chatty\Http\Controllers\ProfileController@getEdit',
	'as' => 'profile.edit',
	'middleware' => ['auth'],
]);

Route::post('/profile/edit', [
	'uses' => '\chatty\Http\Controllers\ProfileController@postEdit',
	'middleware' => ['auth'],
]);


/**FRIENDS **/

Route::get('/friends', [
	'uses' => '\chatty\Http\Controllers\FriendController@getIndex',
	'as' => 'friend.index',
	'middleware' => ['auth'],
]);

Route::get('/friends/add/{username}', [
	'uses' => '\chatty\Http\Controllers\FriendController@getAdd',
	'as' => 'friend.add',
	'middleware' => ['auth'],
]);

Route::get('/friends/accept/{username}', [
	'uses' => '\chatty\Http\Controllers\FriendController@getAccept',
	'as' => 'friend.accept',
	'middleware' => ['auth'],
]);

Route::post('/friends/delete/{username}', [
	'uses' => '\chatty\Http\Controllers\FriendController@postDelete',
	'as' => 'friend.delete',
	'middleware' => ['auth'],
]);

/** STATUSES **/

Route::post('/status', [
	'uses' => '\chatty\Http\Controllers\StatusController@postStatus',
	'as' => 'status.post',
	'middleware' => ['auth'],
]);

Route::post('/status/{statusId}/reply', [
	'uses' => '\chatty\Http\Controllers\StatusController@postReply',
	'as' => 'status.reply',
	'middleware' => ['auth'],
]);

Route::get('/status/{statusId}/like', [
	'uses' => '\chatty\Http\Controllers\StatusController@getLike',
	'as' => 'status.like',
	'middleware' => ['auth'],
	]);