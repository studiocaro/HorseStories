<?php

Route::post('follows', ['as' => 'follows.store', 'uses' => 'FollowsController@store']);
Route::delete('follows/{id}', ['as' => 'follows.destroy', 'uses' => 'FollowsController@destroy']);