<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;

Route::prefix('characters')->group(function () {
    Route::get('/', [CharacterController::class, 'index']);
    Route::get('/{id}', [CharacterController::class, 'show']);
});
