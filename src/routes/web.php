<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Sewega\Signator\Http\Controllers\SignedLinksController;

Route::get('/signed-links/{signedLink:uuid}', [SignedLinksController::class, 'index'])->name('signed-links.index');
Route::post('/signed-links/{signedLink:uuid}', [SignedLinksController::class, 'store'])->name('signed-links.store');
Route::put('/signed-links/{signedLink:uuid}', [SignedLinksController::class, 'update'])->name('signed-links.update');
Route::delete('/signed-links/{signedLink:uuid}', [SignedLinksController::class, 'destroy'])->name('signed-links.delete');
