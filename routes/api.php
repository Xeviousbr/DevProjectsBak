<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/teste', function () {
    return response()->json(['message' => 'ok']);
});

Route::get('/login', [AuthController::class, 'login']);

Route::get('/seta', [AuthController::class, 'seta']);

// NÃO TA FUNCIONANDO POR POST QUE VAI PARAR NO https://devprojects.com/
// Route::post('/login', [AuthController::class, 'login']);
