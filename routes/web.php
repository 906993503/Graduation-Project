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

Route::get('/', function () {
    return view('index');
});
Route::get('/manage', function () {
    return view('manage');
});
Auth::routes(['verify' => true]);

Route::post('/open/getUser', 'Open\OpenController@getUser');
Route::post('/open/getType', 'Open\OpenController@getType');
Route::post('/open/getArticleItemList', 'Open\OpenController@getArticleItemList');
Route::post('/open/getArticle', 'Open\OpenController@getArticle');
Route::post('/open/toSearch', 'Open\OpenController@toSearch');

Route::post('/manage/getUserList', 'Manage\ManageController@getUserList');
Route::post('/manage/banUser', 'Manage\ManageController@banUser');
Route::post('/manage/saveUser', 'Manage\ManageController@saveUser');
Route::post('/manage/getTypeList', 'Manage\ManageController@getTypeList');
Route::post('/manage/saveType', 'Manage\ManageController@saveType');
Route::post('/manage/delType', 'Manage\ManageController@delType');
Route::post('/manage/getArticleList', 'Manage\ManageController@getArticleList');
Route::post('/manage/saveArticle', 'Manage\ManageController@saveArticle');
Route::post('/manage/banArticle', 'Manage\ManageController@banArticle');
Route::post('/manage/delArticle', 'Manage\ManageController@delArticle');
Route::post('/manage/getCommentList', 'Manage\ManageController@getCommentList');
Route::post('/manage/delComment', 'Manage\ManageController@delComment');

Route::post('/home/upload', 'Home\HomeController@upload');
Route::post('/home/uploadFace', 'Home\HomeController@uploadFace');
Route::post('/home/uploadAvatar', 'Home\HomeController@uploadAvatar');
Route::post('/home/saveArticle', 'Home\HomeController@saveArticle');
Route::post('/home/editArticle', 'Home\HomeController@editArticle');
Route::post('/home/getCollectArticleItemList', 'Home\HomeController@getCollectArticleItemList');
Route::post('/home/getMyArticleItemList', 'Home\HomeController@getMyArticleItemList');
Route::post('/home/collectThis', 'Home\HomeController@collectThis');
Route::post('/home/addComment', 'Home\HomeController@addComment');
Route::post('/home/saveUserName', 'Home\HomeController@saveUserName');
Route::post('/home/resetPw', 'Home\HomeController@resetPw');