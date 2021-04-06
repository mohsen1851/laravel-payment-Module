<?php


Route::prefix("payment")->name('payments.')->group(function () {
    Route::any("/callback", "PaymentController@callback")->name('callback');
    Route::get("/index", ['uses'=>"PaymentController@index","as"=>'index']);
});

Route::prefix("purchase")->name('purchases.')->group(function () {
    Route::get("/index", ['uses'=>"PaymentController@purchases","as"=>'index']);
});
