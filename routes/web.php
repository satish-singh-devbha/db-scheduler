<?php

use Illuminate\Support\Facades\Route;
use Satishsinghdevbha\DbScheduler\Http\Controllers\IndexController;


// Route::get("/db-scheduler", [IndexController::class, 'index'])->middleware('guest', 'web');

Route::get('/db-scheduler', [IndexController::class, 'index'])->middleware('guest', 'web');
Route::post('/db-scheduler', [IndexController::class, 'store'])->middleware('guest', 'web');
Route::get('/db-scheduler/{id}', [IndexController::class, 'show'])->middleware('guest', 'web');
Route::put('/db-scheduler/{id}', [IndexController::class, 'update'])->middleware('guest', 'web');
Route::delete('/db-scheduler/{id}', [IndexController::class, 'destroy'])->middleware('guest', 'web');