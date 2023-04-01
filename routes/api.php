<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MunicipioController;

Route::name('municipio.')->group(function () {
    Route::post('municipios', MunicipioController::class)
        ->name('buscar');
});
