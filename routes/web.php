<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
 * AUTH
 */
//Auth::routes();
Route::get('login', [
    'as'    => 'login',
    'uses'  => 'Auth\LoginController@showLoginForm'
]);
Route::post('login', [
    'as'    => 'login',
    'uses'  => 'Auth\LoginController@login'
]);
Route::get('logout', 'Auth\LoginController@logout');

/**
 * INDEX
 */
Route::resource('/', 'IndexController', [
    'only'  => ['index'],
    'names' => [
        'index' => 'home'
    ]
]);

/**
 * PORTFOLIOS
 */
Route::resource('/portfolios', 'PortfolioController', [
    'parameters' => [
        'portfolios' => 'alias'
    ]
]);

/**
 * ARTICLES
 */
Route::resource('/articles', 'ArticlesController', [
    'parameters' => [
        'articles' => 'alias'
    ]
]);
Route::get('/articles/cat/{cat_alias?}', [
    'as'    => 'articlesCat',
    'uses'  => 'ArticlesController@index',
])->where('cat_alias', '[\w-]+');

/**
 * COMMENTS
 */
Route::resource('/comment', 'CommentController', [
    'only' => ['store']
]);

/**
 * CONTACTS
 */
Route::match(['get', 'post'], '/contacts', [
    'as'    => 'contacts',
    'uses'  => 'ContactsController@index'
]);