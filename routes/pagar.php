<?php
// PAYTM
Route::post('pagar/payment', 'PagarController@make_payment')->name('pagar.payment'); //pagar.payment;
Route::post('pagar/payment/pending', 'PagarController@paymentValidation')->name('pagar.pending'); //pagar.payment;
Route::post('pagar/payment/status', 'PagarController@paymentCallback')->name('pagar.callback'); // pagar callback
Route::get('pagar/installment', 'PagarController@getInstallments')->name('pagar.installment'); //pagar.payment;