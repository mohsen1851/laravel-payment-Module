<?php


Route::middleware('auth')->prefix("settlement")->name('settlements.')->group(function () {
    Route::get("/index", ["uses" => 'SettlementController@index', "as" => "index"]);
    Route::get("/create", ["uses" => 'SettlementController@create', "as" => "create"]);
    Route::get("/{settlement}/edit", ["uses" => 'SettlementController@edit', "as" => "edit"]);
    Route::patch("/{settlement}", ["uses" => 'SettlementController@update', "as" => "update"]);
    Route::post("/store", "SettlementController@store")->name('store');
});
