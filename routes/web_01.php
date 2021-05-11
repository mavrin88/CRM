<?php

// Зарегистрированные пользователи - мидлвар «auth»
Route::group(['middleware' => ['auth']], function () {

    Route::get('/{any}', 'PagesController@index')->where('any', '.*');

    Route::get('journal', 'PagesController@journal');
    Route::get('permissions', 'PagesController@permissions');
    Route::get('agregator', 'PagesController@agregator');
    Route::get('kassa', 'PagesController@kassa');
    Route::get('kassa_setings', 'PagesController@kassaSetings');
    Route::get('users', 'PagesController@users');
    Route::get('roles', 'PagesController@roles');
    Route::get('useraccess', 'PagesController@useraccess');
    Route::get('page-region', 'PagesController@regions');
    Route::get('get', 'VueController@get');
    Route::get('get_email', 'VueController@getEmail');
    Route::post('getone', 'VueController@getOne');
    Route::get('testing', 'PagesController@testing');
    Route::get('promouter', 'PagesController@promoter');
});
