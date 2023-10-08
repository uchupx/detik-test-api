<?php
use Config\Config;
use Routes\Route;

new Config();

Route::get('/transactions', 'App\Controller\TransactionController::getAll');
Route::get('/transactions/status', 'App\Controller\TransactionController::status');
Route::post('/transactions', 'App\Controller\TransactionController::post');
Route::get('/transaction/{:segmen}', 'App\Controller\TransactionController::get');

Route::run();
