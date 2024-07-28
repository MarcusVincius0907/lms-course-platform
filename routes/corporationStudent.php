<?php

Route::get('corp/{corporation_path}/login', 'CorporationStudentController@login' )->name('corporationStudent.login');
Route::post('corp/{corporation_path}/auth', 'CorporationStudentController@auth' )->name('corporationStudent.auth');
Route::get('corp/{corporation_path}/reset', 'CorporationStudentController@password_reset' )->name('corporationStudent.reset');

Route::group(['middleware' => ['auth', 'checkCorpStudent']], function () {
  Route::get('corp/{corporation_path}/home', 'CorporationStudentController@home' )->name('corporationStudent.home');
  //lesson_details
  Route::get('corp/{corporation_path}/lesson/{slug}', 'CorporationStudentController@lesson_details')->name('corporationStudent.lesson_details');
});